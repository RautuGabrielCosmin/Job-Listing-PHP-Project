<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
    protected $routes = array();

    /**
     * Add a new route
     * @param string $method
     * @param string $uri
     * @param string $action
     * @return void
     */
    public function registerRoute($method, $uri, $action)
    {
        list($controller, $controllerMethod) = explode("@", $action);
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
        ];
    } //end of registerRoute($method, $uri, $controller)


    /**
     * Add a HTTP GET request for the router
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function get($uri, $controller)
    {
        $this->registerRoute('GET', $uri, $controller);
    } //end of get($uri, $controller)

    /**
     * Add a HTTP POST request for the router
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->registerRoute('POST', $uri, $controller);
    } //end of post($uri, $controller)

    /**
     * Add a HTTP PUT request for the router
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function put($uri, $controller)
    {
        $this->registerRoute('PUT', $uri, $controller);
    } //end of put($uri, $controller)

    /**
     * Add a HTTP DELETE request for the router
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function delete($uri, $controller)
    {
        $this->registerRoute('DELETE', $uri, $controller);
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
