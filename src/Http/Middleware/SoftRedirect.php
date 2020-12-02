<?php

namespace Oidc\Http\Middleware;

use Closure;
use ReflectionMethod;
use ReflectionFunction;
use Illuminate\Http\{Request, Response};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\{Controller, Route, Router, Redirector};

/**
 * Make sure any custom values applied to the request object past into this class get transposed onto any Controller
 * type-hinted Request objects as well
 *
 * Class SoftRedirect
 * @package Oidc\Http\Middleware
 */
class SoftRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route();
        $parameters = $this->getTargetParameters($route);

        foreach ($parameters as $name => $parameter) {
            $parameter instanceof Request && $this->rebindRequests($request, $parameter);
        }

        return $next($request);
    }

    /**
     * @param Request $currentRequest
     * @param Request $newRequest
     */
    protected function rebindRequests(Request $currentRequest, Request $newRequest)
    {
        $newRequest->query->add($currentRequest->query->all());

        app()->resolving(FormRequest::class, function (Request $originalRequest, Container $app) use ($currentRequest) {
            $formRequest = FormRequest::createFrom($app['request'], $originalRequest);
            $formRequest->setContainer($app)->setRedirector($app->make(Redirector::class));
            $formRequest->query->add($currentRequest->query->all());
        });

        app()->bind(get_class($newRequest), function () use ($newRequest) {
            return $newRequest;
        });
    }

    /**
     * @param Route $route
     * @return array
     */
    protected function getTargetParameters(Route $route)
    {
        ['controller' => $controller, 'method' => $method] = $this->getTarget($route);
        $parameters = $route->parametersWithoutNulls();
        $reflector = $controller instanceof Closure ? app(ReflectionFunction::class, ['name' => $controller])
            : app(ReflectionMethod::class, ['class_or_method' => $controller, 'name' => $method]);

        return $route->resolveMethodDependencies($parameters, $reflector);
    }

    /**
     * @param Route $route
     * @return array
     */
    protected function getTarget(Route $route)
    {
        $controller = static::getController($route);
        $method = $route->getActionMethod();

        return compact('controller', 'method');
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array $parameters
     * @return Response
     */
    public static function response(string $uri, $method = 'GET', array $parameters = [])
    {
        $request = Request::create($uri, $method, $parameters);
        $request = static::applyMiddleware($request);
        $response = app(Router::class)->dispatch($request);

        return response($response->getContent(), $response->getStatusCode(), $response->headers->all());
    }

    /**
     * @param Request $request
     * @return Request
     */
    protected static function applyMiddleware(Request $request)
    {
        $route = app(Router::class)->getRoutes()->match($request);
        $controller = static::getController($route);

        if ($controller instanceof Controller) {
            $controller->middleware(static::class);
        } else {
            $request->setRouteResolver(function () use ($route) {
                return $route;
            });

            $request = app(static::class)->handle($request, function (Request $request) {
                return $request;
            });
        }

        return $request;
    }

    /**
     * @param Route $route
     * @return callable|Controller
     */
    protected static function getController(Route $route)
    {
        $action = $route->getAction('uses');

        return is_callable($action) ? $action : $route->getController();
    }
}
