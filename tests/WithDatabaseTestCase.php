<?php

namespace Tests;

use Faker\Factory as FakerFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

abstract class WithDatabaseTestCase extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    protected $faker;

    public function setUp(): void
    {
        parent::setUp();
        $this->faker = FakerFactory::create();
    }
}
