<?php

use Framework\Framework;
use Framework\StringResponseListener;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\ErrorListener;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

$container = new ContainerBuilder();
$container->register('context', RequestContext::class);
$container->register('matcher', UrlMatcher::class)
    ->setArguments([$routes, new Reference('context')])
;
$container->register('request_stack', RequestStack::class);
$container->register('controller_resolver', ControllerResolver::class);
$container->register('argument_resolver', ArgumentResolver::class);

$container->register('listener.router', RouterListener::class)
    ->setArguments([new Reference('matcher'), new Reference('request_stack')])
;
$container->register('listener.response', ResponseListener::class)
    ->setArguments(['%charset%'])
;
$container->register('listener.exception', ErrorListener::class)
    ->setArguments(['Calendar\Controller\ErrorController::exception'])
;
$container->register('dispatcher', EventDispatcher::class)
    ->addMethodCall('addSubscriber', [new Reference('listener.router')])
    ->addMethodCall('addSubscriber', [new Reference('listener.response')])
    ->addMethodCall('addSubscriber', [new Reference('listener.exception')])
;
$container->register('framework', Framework::class)
    ->setArguments([
        new Reference('dispatcher'),
        new Reference('controller_resolver'),
        new Reference('request_stack'),
        new Reference('argument_resolver'),
    ])
;

$container->register('listener.string_response', StringResponseListener::class);
$container->getDefinition('dispatcher')
    ->addMethodCall('addSubscriber', [new Reference('listener.string_response')])
;

return $container;
