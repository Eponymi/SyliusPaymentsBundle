<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/*
$controller = $resolver->getController($request);
$arguments = $resolver->getArguments($request, $controller);
// first param below is an array([0] => , [1] => );
$response = call_user_func_array($controller, $arguments);
 */

namespace Sylius\Bundle\PaymentsBundle\Gateway;

use Symfony\Bundle\FrameworkBundle\Controller\ControllerResolver;
use Sylius\Bundle\PaymentsBundle\Model\Payment;

/**
 * MethodResolver.
 *
 * This implementation uses a string to determine the method or function to 
 * execute and uses the request attributes to determine the method or function arguments.
 *
 * @author Dylan Johnson <eponymi.dev@gmail.com>
 */
class MethodResolver
{
	protected $resolver;

	/**
     * Constructor.
     *
     * @param ControllerResolver    $parser   A ControllerResolver instance
     */
    public function __construct(ControllerResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Returns the Controller instance associated with a Request.
     *
     * This method turns a string into a callable representing the controller.
     *
     * @param $controller A string naming the controller
     *
     * @return mixed|Boolean A PHP callable representing the Controller,
     *                       or false if this resolver is not able to determine the controller
     *
     * @throws \InvalidArgumentException|\LogicException If the controller can't be found
     */
    public function getController($controller)
    {
        if (is_array($controller) || (is_object($controller) && method_exists($controller, '__invoke'))) {
            return $controller;
        }

        if (false === strpos($controller, ':')) {
            if (method_exists($controller, '__invoke')) {
                return new $controller;
            } elseif (function_exists($controller)) {
                return $controller;
            }
        }
        
        if (3 = count($parts = explode(':', $controller))) {
            
        }

        list($controller, $method) = $this->createController($controller);

        if (!method_exists($controller, $method)) {
            throw new \InvalidArgumentException(sprintf('Method "%s::%s" does not exist.', get_class($controller), $method));
        }

        return array($controller, $method);
    }

    /**
     * Returns the arguments to pass to the controller.
     *
     * @param Request $request    A Request instance
     * @param mixed   $controller A PHP callable
     *
     * @return array
     *
     * @throws \RuntimeException When value for argument given is not provided
     *
     * @api
     */
    public function getArguments(Request $request, $controller)
    {
        if (is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (is_object($controller) && !$controller instanceof \Closure) {
            $r = new \ReflectionObject($controller);
            $r = $r->getMethod('__invoke');
        } else {
            $r = new \ReflectionFunction($controller);
        }

        return $this->doGetArguments($request, $controller, $r->getParameters());
    }

    protected function doGetArguments(Request $request, $controller, array $parameters)
    {
        $attributes = $request->attributes->all();
        $arguments = array();
        foreach ($parameters as $param) {
            if (array_key_exists($param->name, $attributes)) {
                $arguments[] = $attributes[$param->name];
            } elseif ($param->getClass() && $param->getClass()->isInstance($request)) {
                $arguments[] = $request;
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                if (is_array($controller)) {
                    $repr = sprintf('%s::%s()', get_class($controller[0]), $controller[1]);
                } elseif (is_object($controller)) {
                    $repr = get_class($controller);
                } else {
                    $repr = $controller;
                }

                throw new \RuntimeException(sprintf('Controller "%s" requires that you provide a value for the "$%s" argument (because there is no default value or because there is a non optional argument after this one).', $repr, $param->name));
            }
        }

        return $arguments;
    }

    /**
     * Returns a callable for the given controller.
     *
     * @param string $controller A Controller string
     *
     * @return mixed A PHP callable
     *
     * @throws \InvalidArgumentException
     */
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            throw new \InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }

        list($class, $method) = explode('::', $controller, 2);

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        return array(new $class(), $method);
    }
}
