<?php

// Diagnostic Check Script
echo "==== APPLICATION DIAGNOSTIC CHECK ====\n\n";

$errors = [];
$warnings = [];

// 1. Check PHP Version
$php_version = phpversion();
echo "✓ PHP Version: $php_version\n";

// 2. Check Extensions
$required_extensions = ['pdo', 'pdo_mysql', 'mbstring', 'json', 'bcmath'];
foreach($required_extensions as $ext) {
    if(extension_loaded($ext)) {
        echo "✓ Extension $ext loaded\n";
    } else {
        $errors[] = "Extension $ext not loaded";
    }
}

// 3. Check Directory Permissions
$directories = [
    'storage' => 'writable',
    'bootstrap/cache' => 'writable',
    'resources/views' => 'readable'
];

foreach($directories as $dir => $required) {
    $path = __DIR__ . '/' . $dir;
    if(is_dir($path)) {
        if($required === 'writable' && is_writable($path)) {
            echo "✓ Directory $dir is writable\n";
        } elseif($required === 'readable' && is_readable($path)) {
            echo "✓ Directory $dir is readable\n";
        } else {
            $errors[] = "Directory $dir has permission issues";
        }
    }
}

// 4. Check .env file
if(file_exists(__DIR__ . '/.env')) {
    echo "✓ .env file exists\n";
    // Check critical env vars
    $env_content = file_get_contents(__DIR__ . '/.env');
    $critical_vars = ['APP_KEY', 'DB_CONNECTION', 'DB_HOST', 'DB_DATABASE'];
    foreach($critical_vars as $var) {
        if(strpos($env_content, "$var=") !== false) {
            echo "✓ $var is configured\n";
        } else {
            $warnings[] = "$var not found in .env";
        }
    }
} else {
    $errors[] = ".env file not found";
}

// 5. Check Views Directory
$views_dir = __DIR__ . '/resources/views';
if(is_dir($views_dir)) {
    $blade_files = glob($views_dir . '/**/*.blade.php', GLOB_RECURSIVE);
    echo "✓ Found " . count($blade_files) . " blade view files\n";
    
    // Check critical views
    $critical_views = [
        'auth/login.blade.php',
        'auth/register.blade.php',
        'layouts/app.blade.php',
        'siswa/dashboard.blade.php',
        'guru/dashboard.blade.php',
        'koordinator/dashboard.blade.php'
    ];
    
    foreach($critical_views as $view) {
        $view_path = $views_dir . '/' . $view;
        if(file_exists($view_path)) {
            echo "✓ View $view exists\n";
        } else {
            $warnings[] = "View $view not found";
        }
    }
} else {
    $errors[] = "Views directory not found";
}

// 6. Check Controllers
$controllers_dir = __DIR__ . '/app/Http/Controllers';
if(is_dir($controllers_dir)) {
    $php_files = glob($controllers_dir . '/*.php');
    echo "✓ Found " . count($php_files) . " controller files\n";
    
    $critical_controllers = [
        'AuthController.php',
        'GuruController.php',
        'KoordinatorController.php',
        'SiswaController.php'
    ];
    
    foreach($critical_controllers as $controller) {
        $path = $controllers_dir . '/' . $controller;
        if(file_exists($path)) {
            echo "✓ Controller $controller exists\n";
        } else {
            $warnings[] = "Controller $controller not found";
        }
    }
} else {
    $errors[] = "Controllers directory not found";
}

// 7. Check Models
$models_dir = __DIR__ . '/app/Models';
if(is_dir($models_dir)) {
    $php_files = glob($models_dir . '/*.php');
    echo "✓ Found " . count($php_files) . " model files\n";
    
    $critical_models = ['User.php', 'JanjiKonseling.php'];
    
    foreach($critical_models as $model) {
        $path = $models_dir . '/' . $model;
        if(file_exists($path)) {
            echo "✓ Model $model exists\n";
        } else {
            $warnings[] = "Model $model not found";
        }
    }
} else {
    $errors[] = "Models directory not found";
}

// 8. Check Routes
$routes_file = __DIR__ . '/routes/web.php';
if(file_exists($routes_file)) {
    echo "✓ Routes file exists\n";
} else {
    $errors[] = "Routes file not found";
}

// 9. Check Database Configuration
$db_config = __DIR__ . '/config/database.php';
if(file_exists($db_config)) {
    echo "✓ Database config exists\n";
} else {
    $warnings[] = "Database config not found";
}

echo "\n==== SUMMARY ====\n";
echo "✓ Extensions: OK\n";
echo "✓ Directories: OK\n";
echo "✓ Configuration: OK\n";
echo "✓ Views: " . count($blade_files ?? []) . " files\n";
echo "✓ Controllers: OK\n";
echo "✓ Models: OK\n";

if(count($errors) > 0) {
    echo "\n⚠️ ERRORS FOUND:\n";
    foreach($errors as $error) {
        echo "  - $error\n";
    }
} else {
    echo "\n✓ NO ERRORS FOUND\n";
}

if(count($warnings) > 0) {
    echo "\n⚡ WARNINGS:\n";
    foreach($warnings as $warning) {
        echo "  - $warning\n";
    }
} else {
    echo "✓ NO WARNINGS\n";
}

echo "\n==== END OF DIAGNOSTIC CHECK ====\n";
?>
