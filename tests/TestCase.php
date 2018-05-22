<?php

namespace Ponich\Eloquent\Traits\Tests;

use Ponich\Eloquent\Traits\ServiceProvider as TraitServiceProvider;
use Ponich\Eloquent\Traits\Tests\Models\Post;
use Ponich\Eloquent\Traits\Tests\Models\User;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup global congigurate
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->withFactories(__DIR__ . '/database/factories');
        $this->seeds();
    }

    /**
     * Run database seeds
     *
     * @return void
     */
    public function seeds()
    {
        factory(User::class, 3)->create();
        factory(Post::class, 6)->create();
    }

    /**
     * Load service providers
     *
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            TraitServiceProvider::class,
        ];
    }

    /**
     * Configure environments
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => env('DB_CONNECTION'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'database' => env('DB_DATABASE'),
            'port' => env('DB_PORT', '3306'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
        ]);
    }
}