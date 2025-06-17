<?php

namespace Framework;

use App\Controllers\ErrorController;
use Framework\Middleware\Authorize;


class Router
{
    protected $routes = array();

    /**
     * Add a new route
     * @param string $method
     * @param string $uri
     * @param string $action
     * @param array $middleware
     * @return void
     */
    public function registerRoute($method, $uri, $action, $middleware = [])
    {
        list($controller, $controllerMethod) = explode("@", $action);
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod,
            'middleware' => $middleware
        ];
    } //end of registerRoute($method, $uri, $controller)


    /**
     * Add a HTTP GET request for the router
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function get($uri, $controller, $middleware = [])
    {
        $this->registerRoute('GET', $uri, $controller, $middleware);
    } //end of get($uri, $controller)

    /**
     * Add a HTTP POST request for the router
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function post($uri, $controller, $middleware = [])
    {
        $this->registerRoute('POST', $uri, $controller, $middleware);
    } //end of post($uri, $controller)

    /**
     * Add a HTTP PUT request for the router
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function put($uri, $controller, $middleware = [])
    {
        $this->registerRoute('PUT', $uri, $controller, $middleware);
    } //end of put($uri, $controller)

    /**
     * Add a HTTP DELETE request for the router
     * @param string $uri
     * @param string $controller
     * @param array $middleware
     * @return void
     */
    public function delete($uri, $controller, $middleware = [])
    {
        $this->registerRoute('DELETE', $uri, $controller, $middleware);
    } //end of delete($uri, $controller)

    /**
     * Route the HTTP requests
     * @param string $uri
     * @param string $method
     * @return void
     */
    public function route($uri)
    {

        $requestMethod = $_SERVER['REQUEST_METHOD'];

        //Check for _method input
        if ($requestMethod === 'POST' && isset($_POST['_method'])) {
            //Override the request method with the value of _method
            $requestMethod = strtoupper($_POST['_method']);
        }

        foreach ($this->routes as $route) {
            //Split the current URI into segments
            $uriSegments = explode('/', trim($uri, '/'));
            //Split the current Route URI into segments (current itteration in the loop) 
            $routeSegments = explode('/', trim($route['uri'], '/'));
            $match = true;
            //Check if the number of segments match the URI
            if (
                count($uriSegments) === count($routeSegments) &&
                strtoupper($route['method'] === $requestMethod)
            ) {
                $params = [];
                $match = true;
                for ($i = 0; $i < count($uriSegments); $i++) {
                    //if the URI do not match and there is no parameter/param
                    if (
                        $routeSegments[$i] !== $uriSegments[$i] &&
                        !preg_match('/\{(.+?)\}/', $routeSegments[$i])
                    ) {
                        $match = false;
                        break;
                    }
                    //check for the param and add to $params array
                    if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];
                    }
                }
                if ($match) {
                    foreach ($route['middleware'] as $middleware) {
                        (new Authorize())->handle($middleware);
                    }

                    //Extract controller and controller method
                    $controller = 'App\\Controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];
                    //Instatiate the controller and call the method
                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);
                    return;
                }
            } //end of route($uri)
        }
        ErrorController::notFound404();
    } //end of route($uri, $method)
}
