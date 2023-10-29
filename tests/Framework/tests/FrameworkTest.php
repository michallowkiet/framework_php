<?php

namespace Framework\Tests;

use Calendar\Controller\LeapYearController;
use Framework\Framework;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

class FrameworkTest extends TestCase
{
    public function testNotFoundHandling(): void
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

        $response = $framework->handle(new Request());

        $this->assertSame(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    public function testErrorHandling(): void
    {
        $framework = $this->getFrameworkForException(new \RuntimeException());

        $response = $framework->handle(new Request());

        $this->assertSame(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());
    }

    public function testControllerResponse(): void
    {
        $matcher = $this->createMock(UrlMatcherInterface::class);

        $matcher
          ->expects($this->once())
          ->method('match')
          ->will($this->returnValue([
            '_route' => 'is-leap-year/{year}',
            'year' => '2000',
            '_controller' => [new LeapYearController(), 'index'],
          ]));

        $matcher
          ->expects($this->once())
          ->method('getContext')
          ->will($this->returnValue($this->createMock(RequestContext::class)));

        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $framework = new Framework($matcher, $controllerResolver, $argumentResolver);

        $response = $framework->handle(new Request());

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertStringContainsString('Tak, jest to rok przestępny', $response->getContent());
    }

    private function getFrameworkForException(\Exception $exception): Framework
    {
        $matcher = $this->createMock(UrlMatcherInterface::class);

        $matcher
          ->expects($this->once())
          ->method('match')
          ->will($this->throwException($exception));

        $matcher
          ->expects($this->once())
          ->method('getContext')
          ->will($this->returnValue($this->createMock(RequestContext::class)));

        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);

        return new Framework($matcher, $controllerResolver, $argumentResolver);
    }
}
