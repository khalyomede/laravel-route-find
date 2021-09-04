<?php

namespace Khalyomede\LaravelRouteFind\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class RouteFind extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'route:find {url} {--m|method=GET : The HTTP method of the route.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find the route that match an URL.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     *
     * @see https://stackoverflow.com/a/36476224
     */
    public function handle(): int
    {
        $url = $this->argument("url");
        $method = $this->option("method");
        $request = app(Request::class)->create($url, $method);

        try {
            /**
             * @var Route
             */
            $route = app(Router::class)->getRoutes()->match($request);
        } catch (NotFoundHttpException $exception) {
            $this->error("No route found.");

            return 1;
        } catch (MethodNotAllowedHttpException $exception) {
            $this->error($exception->getMessage());

            return 2;
        }

        self::getFilePathWithNumberFromRoute($route);

        $this->table([
            "controller",
            "path",
            "name",
            "middlewares",
        ], [
            [
                self::getFilePathWithNumberFromRoute($route),
                $route->uri(),
                $route->getName(),
                implode(", ", $route->gatherMiddleware()),
            ]
        ]);

        return 0;
    }

    private static function getMethodLineNumberFromFile(string $file, string $method): int
    {
        $content = File::get($file);
        $lines = explode("\n", $content);

        foreach ($lines as $index => $line) {
            if (str_contains($line, "public function $method")) {
                return $index + 1;
            }
        }

        return -1;
    }

    private static function getFilePathWithNumberFromRoute(Route $route): string
    {
        $controller = $route->getController();
        $classPath = get_class($controller);
        $filePath = preg_replace("/^App/", "app", str_replace("\\", DIRECTORY_SEPARATOR, $classPath));

        if (App::runningUnitTests() && is_string($filePath)) {
            $filePath = preg_replace("/^Tests/", "tests", $filePath); // For feature tests
        }

        $filePath =  "$filePath.php";

        $method = $route->getActionMethod();
        $lineNumber = self::getMethodLineNumberFromFile($filePath, $method);

        return "$filePath:$lineNumber";
    }
}
