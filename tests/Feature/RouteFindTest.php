<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Illuminate\Testing\PendingCommand;
use Tests\Controllers\ContactController;
use Tests\TestCase;

final class RouteFindTest extends TestCase
{
    public function testItReturnsRouteNotFoundIfNotRouteMatches(): void
    {
        /**
         * @var PendingCommand
         */
        $command = $this->artisan("route:find /foo");

        $command->expectsOutput("No route found.")
            ->assertExitCode(1);
    }

    public function testItReturnsMethodNotAllowedIfMethodDoesNotMatches(): void
    {
        Route::post('/contact', [ContactController::class, "store"]);

        /**
         * @var PendingCommand
         */
        $command = $this->artisan("route:find /contact");

        $command->expectsOutput("The GET method is not supported for this route. Supported methods: POST.")
            ->assertExitCode(2);
    }

    public function testItReturnsRouteFound(): void
    {
        Route::get('/contact', [ContactController::class, "index"]);

        /**
         * @var PendingCommand
         */
        $command = $this->artisan("route:find /contact");

        $command->expectsTable([
            "controller",
            "path",
            "name",
            "middlewares",
        ], [
            [
                "tests/Controllers/ContactController.php:9",
                "contact",
                "",
                ""
            ]
        ])
            ->assertExitCode(0);
    }

    public function testItReturnsRouteWithMethod(): void
    {
        Route::post('/contact', [ContactController::class, "store"])->name("contact.store");

        /**
         * @var PendingCommand
         */
        $command = $this->artisan("route:find /contact --method=POST");

        $command->expectsTable([
            "controller",
            "path",
            "name",
            "middlewares",
        ], [
            [
                "tests/Controllers/ContactController.php:14",
                "contact",
                "contact.store",
                ""
            ]
        ])
            ->assertExitCode(0);
    }
}
