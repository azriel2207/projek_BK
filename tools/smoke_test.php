<?php

function http_request($method, $url, $data = null, $cookies = []) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);

    $headers = [];
    if (!empty($cookies)) {
        $cookieHeader = [];
        foreach ($cookies as $k => $v) {
            $cookieHeader[] = $k.'='.$v;
        }
        $headers[] = 'Cookie: '.implode('; ', $cookieHeader);
    }

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $resp = curl_exec($ch);

    if ($resp === false) {
        $err = curl_error($ch);
        curl_close($ch);
        throw new Exception($err);
    }

    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($resp, 0, $header_size);
    $body = substr($resp, $header_size);
    curl_close($ch);
    return [$status, $header, $body];
}

$base = 'http://127.0.0.1:8001';
try {
    // Step 1: GET / to obtain cookies (XSRF-TOKEN + laravel_session)
    list($status, $header, $body) = http_request('GET', $base.'/');
    preg_match_all('/Set-Cookie:\s*([^=]+)=([^;]+);/i', $header, $matches, PREG_SET_ORDER);
    $cookies = [];
    foreach ($matches as $m) {
        $cookies[$m[1]] = urldecode($m[2]);
    }

    echo "GET / -> HTTP $status\n";

    $xsrf = isset($cookies['XSRF-TOKEN']) ? $cookies['XSRF-TOKEN'] : null;
    $session = isset($cookies['laravel_session']) ? $cookies['laravel_session'] : null;

    if (!$xsrf) {
        echo "No XSRF-TOKEN cookie found\n";
    }

    // Helper to perform POST with CSRF header
    $doPost = function($path, $fields) use ($base, &$xsrf, &$session) {
        $url = $base.$path;
        $cookies = [];
        if ($session) $cookies['laravel_session'] = $session;
        if ($xsrf) $cookies['XSRF-TOKEN'] = $xsrf;

        // Add X-XSRF-TOKEN header
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $header = [];
        if ($xsrf) $header[] = 'X-XSRF-TOKEN: '.urldecode($xsrf);
        $cookieHeader = [];
        if ($session) $cookieHeader[] = 'laravel_session='.$session;
        if ($xsrf) $cookieHeader[] = 'XSRF-TOKEN='.$xsrf;
        if (!empty($cookieHeader)) $header[] = 'Cookie: '.implode('; ', $cookieHeader);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $resp = curl_exec($ch);
        if ($resp === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new Exception($err);
        }
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $h = substr($resp, 0, $header_size);
        $b = substr($resp, $header_size);
        // Parse Set-Cookie headers to update session/xsrf for subsequent requests
        if (preg_match_all('/Set-Cookie:\s*([^=]+)=([^;]+);/i', $h, $m, PREG_SET_ORDER)) {
            foreach ($m as $c) {
                $name = $c[1];
                $val = urldecode($c[2]);
                if ($name === 'laravel_session') $session = $val;
                if ($name === 'XSRF-TOKEN') $xsrf = $val;
            }
        }

        curl_close($ch);
        return [$status, $h, $b];
    };

    echo "\n-- Testing registration for guru_bk --\n";
    $fields = [
        'name' => 'Smoke Guru',
        'email' => 'smoke.guru@example.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'guru_bk'
    ];
    list($s,$h,$b) = $doPost('/register', $fields);
    echo "POST /register -> HTTP $s\n";
    if (preg_match('/Location:\s*(.*)/i', $h, $m)) {
        echo "Redirect to: ".$m[1]."\n";
    }

    echo "\n-- Testing registration for siswa --\n";
    $fields = [
        'name' => 'Smoke Siswa',
        'email' => 'smoke.siswa@example.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'role' => 'siswa',
        'nis' => 'NIS-SMOKE-001',
        'kelas' => '10A'
    ];
    list($s,$h,$b) = $doPost('/register', $fields);
    echo "POST /register (siswa) -> HTTP $s\n";
    if (preg_match('/Location:\s*(.*)/i', $h, $m)) {
        echo "Redirect to: ".$m[1]."\n";
    }

    echo "\nSmoke test completed.\n";
        // Additional: test login for guru and siswa using credentials we registered
        echo "\n-- Testing logout then login for guru --\n";
        list($s,$h,$b) = $doPost('/logout', []);
        echo "POST /logout -> HTTP $s\n";
        list($s,$h,$b) = $doPost('/login', ['email' => 'smoke.guru@example.test', 'password' => 'password123']);
        echo "POST /login (guru) -> HTTP $s\n";
        if (preg_match('/Location:\s*(.*)/i', $h, $m)) echo "Redirect to: ".$m[1]."\n";

        echo "\n-- Testing logout then login for siswa (with NIS) --\n";
        list($s,$h,$b) = $doPost('/logout', []);
        echo "POST /logout -> HTTP $s\n";
        list($s,$h,$b) = $doPost('/login', ['email' => 'smoke.siswa@example.test', 'password' => 'password123', 'nis' => 'NIS-SMOKE-001']);
        echo "POST /login (siswa) -> HTTP $s\n";
        if (preg_match('/Location:\s*(.*)/i', $h, $m)) echo "Redirect to: ".$m[1]."\n";
} catch (Exception $e) {
    echo "Error: ".$e->getMessage()."\n";
}
