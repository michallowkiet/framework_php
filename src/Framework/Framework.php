<?php

namespace Framework;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Framework implements HttpKernelInterface
{
    public function __construct(
        private EventDispatcher $eventDispatcher,
        private UrlMatcherInterface $matcher,
        private ControllerResolverInterface $controllerResolver,
        private ArgumentResolverInterface $argumentResolver
    ) {
    }

    public function handle(
        Request $request,
        $type = HttpKernelInterface::MAIN_REQUEST,
        $catch = true
    ) {
        $this->matcher->getContext()->fromRequest($request);

        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $e) {
            $response = new Response('Not Found', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $response = new Response('An error occurred', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->eventDispatcher
            ->dispatch(
                new ResponseEvent($response, $request), 'response');

        return $response;
    }
}
