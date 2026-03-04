<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure SQLite does not enforce enum constraint on `role` column by
        // converting it to a simple string. This mirrors the production
        // migration which changes the column but skips SQLite due to SQL
        // limitations.
        if (Schema::hasTable('users')) {
            try {
                Schema::table('users', function (Blueprint $table) {
                    $table->string('role')->change();
                });
            } catch (\Exception $e) {
                // ignore if change isn't supported or already applied
            }
        }
    }
}
