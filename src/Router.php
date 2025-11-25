<?php

namespace App;

class Router
{
    private array $routes = [];
    private ?Route $defaultRoute = null;
    private array $globalMiddlewares = [];

    private function _registerRoute(string $method, string $path, Route $route): void
    {
        $this->routes[$method][$path] = $route;
    }

    /**
     * Constructs and registers a route.
     *
     * @param callable(): void $callback
     * @throws \ErrorException
     */
    private function _makeRoute(string $method, string $path, callable $callback): Route
    {
        if (isset($this->routes[$method][$path])) {
            throw new \ErrorException("'{$path}' has been already registered");
        }

        $route = new Route($callback);
        $this->_registerRoute($method, $path, $route);

        return $route;
    }

    /**
     * Register a GET route.
     *
     * @param callable(): void $callback
     */
    public function GET(string $path, callable $callback): Route
    {
        return $this->_makeRoute('GET', $path, $callback);
    }

    /**
     * Register a POST route.
     *
     * @param callable(): void $callback
     */
    public function POST(string $path, callable $callback): Route
    {
        return $this->_makeRoute('POST', $path, $callback);
    }
 
    /**
     * Register a default route, in case no other routes hit.
     *
     * @param callable(): void $callback
     */
    public function DEFAULT(callable $callback): Route
    {
        if (isset($this->defaultRoute)) {
            throw new \ErrorException("A default route has been already registered");
        }

        $route = new Route($callback);
        $this->defaultRoute = $route;

        return $route;
    }

    /**
     * Register a global middleware that runs on all routes.
     *
     * @param callable(callable): void $middleware
     */
    public function middleware(callable $middleware): self
    {
        $this->globalMiddlewares[] = $middleware;
        return $this;
    }
 
    private function match(string $path, string $pattern): ?array
    {
        $types = [
            'int' => '\d+',
            'string' => '[a-zA-Z0-9_-]+',
        ];

        $regex = preg_replace_callback(
            '#:(\w+)(?::(\w+))?#',
            function ($matches) use ($types) {
                $paramName = $matches[1];
                $paramType = $matches[2] ?? null;
                if ($paramType && isset($types[$paramType])) {
                    $pattern = $types[$paramType];
                } else {
                    $pattern = '[^/]+';
                }
                return "(?P<{$paramName}>{$pattern})";
            },
            $pattern
        );

        $regex = "#^{$regex}$#i";
        
        if (preg_match($regex, $path, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            foreach ($params as $key => $value) {
                if (preg_match('/\(\?P<' . preg_quote($key) . '>' . $types['int'] . '\)/', $regex)) {
                    $params[$key] = (int) $value;
                }
            }
            return $params;
        }
        
        return null;
    }

    /**
     * Handle all requests
     */
    public function handle(): void
    {
        global $_ROUTE;
        $path = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $pattern => $route) {
                $params = $this->match($path, $pattern);
                if ($params !== null) {
                    $_ROUTE = $params;
                    $route->fire($this->globalMiddlewares);
                    return;
                }
            }
        }

        if (isset($this->defaultRoute)) {
            $_ROUTE = [];
            $this->defaultRoute->fire($this->globalMiddlewares);
            return;
        }

        http_response_code(404);

        echo <<<'HTML'
        <!DOCTYPE html>
        <html lang="pl">
        <head>
            <meta charset="UTF8">
            <meta key="viewport" content="width=device-width, initial-scale=1.0">
            <title>Not found</title>
        </head>
        <body>
            <h1>Not found</h1>
            <p>This message was displayed because no default callback was set</p>
        </body>
        </html>
        HTML;
        die;
    }
}

class Route
{
    private \Closure $callback;
    private array $middlewares = [];

    /**
     * @param callable(): void $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback(...);
    }

    /**
     * Add middleware to this specific route.
     *
     * @param callable(callable): void $middleware
     */
    public function middleware(callable $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    /**
     * Execute the route with its middlewares.
     *
     * @param array $globalMiddlewares
     */
    public function fire(array $globalMiddlewares = []): void
    {
        $allMiddlewares = array_merge($globalMiddlewares, $this->middlewares);
        
        $handler = $this->callback;
        
        foreach (array_reverse($allMiddlewares) as $middleware) {
            $middleware();
        }
        
        $handler();
    }
}
