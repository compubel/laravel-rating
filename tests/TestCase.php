<?php

namespace Compubel\Rating\Test;

use Compubel\Rating\RatingServiceProvider;
use Compubel\Rating\Test\Models\Member;
use Compubel\Rating\Test\Models\Post;
use Compubel\Rating\Test\Models\User;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'testbench']);
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
    }

    protected function createUser(): User
    {
        return User::create([
            'name' => 'Name'.Str::random(5),
            'email' => Str::random(5).'@example.com',
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        ]);
    }

    protected function createPost(): Post
    {
        return Post::create(['name' => 'Post '.Str::random(5)]);
    }

    protected function createMember(): Member
    {
        return Member::create(['name' => Str::random(10)]);
    }

    /**
     * Define package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            RatingServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
