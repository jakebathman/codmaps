<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Ensure attachment IDs are seeded after each RefreshDatabase run.
     */
    protected bool $seed = true;

    protected string $seeder = \Database\Seeders\AttachmentIDSeeder::class;
}
