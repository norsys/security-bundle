<?php
declare(strict_types = 1);

namespace Norsys\SecurityBundle\Tests\Units\EventListener;

use Norsys\SecurityBundle\Tests\Units\Helpers\RequestHelper;
use Norsys\SecurityBundle\Tests\Units\Test;
use Symfony\Component\HttpFoundation\RedirectResponse;
use mock\Symfony\Bundle\FrameworkBundle\Routing\Router as MockOfRouter;
use mock\Symfony\Component\HttpFoundation\Request as MockOfRequest;
use Norsys\SecurityBundle\EventListener\ComingSoonListener as BaseClass;

/**
 * Class ComingSoonListener
 *
 * @package Norsys\SecurityBundle\Tests\Units\EventListener
 */
class ComingSoonListener extends Test
{
    use RequestHelper;

    /**
     * TestOnComingSoonNotEnabledAndOnMasterRequest
     */
    public function testOnComingSoonNotEnabledAndOnMasterRequest()
    {
        $this
            ->assert('Coming soon param is false and we are on master request.')
            ->given(
                $router = new MockOfRouter,
                $comingSoonEnabled = false,
                $comingSoonAllowedIps = [],
                $request = new MockOfRequest,
                $event = $this->createMockGetResponseEvent($request),
                $this->newTestedInstance($router, $comingSoonEnabled, $comingSoonAllowedIps)
            )
            ->if($resultResponse = $this->testedInstance->onKernelRequest($event))
            ->then
                ->mock($event)
                    ->call('setResponse')
                        ->never;
    }

    /**
     * TestOnComingSoonRouteName
     */
    public function testOnComingSoonRouteName()
    {
        $this
            ->assert('Route name is ::ROUTE_NAME_COMING_SOON.')
            ->given(
                $router = new MockOfRouter,
                $this->calling($router)->matchRequest = ['_route' => BaseClass::ROUTE_NAME_COMING_SOON],
                $comingSoonEnabled = true,
                $comingSoonAllowedIps = [],
                $request = new MockOfRequest,
                $event = $this->createMockGetResponseEvent($request),
                $this->newTestedInstance($router, $comingSoonEnabled, $comingSoonAllowedIps)
            )
            ->if($resultResponse = $this->testedInstance->onKernelRequest($event))
            ->then
                ->mock($event)
                    ->call('setResponse')
                        ->never;
    }

    /**
     * TestOnIpIsAllowed
     */
    public function testOnIpIsAllowed()
    {
        $this
            ->assert('Test when ip is allowed.')
            ->given(
                $router = new MockOfRouter,
                $comingSoonEnabled = true,
                $comingSoonAllowedIps = ['128.0.0.1'],
                $request = new MockOfRequest,
                $event = $this->createMockGetResponseEvent($request),
                $this->calling($request)->getClientIp = '128.0.0.1',
                $this->newTestedInstance($router, $comingSoonEnabled, $comingSoonAllowedIps)
            )
            ->if($result = $this->testedInstance->onKernelRequest($event))
            ->then
                ->mock($event)
                    ->call('setResponse')
                        ->never;
    }

    /**
     * TestOnNoRulesFoundForRedirection
     */
    public function testOnNoRulesFoundForRedirection()
    {
        $this
            ->assert('Test when no roles found for redirection.')
            ->given(
                $router = new MockOfRouter,
                $url = '/ok',
                $this->calling($router)->generate = $url,
                $comingSoonEnabled = true,
                $comingSoonAllowedIps = ['128.0.0.2', '128.0.0.3'],
                $request = new MockOfRequest,
                $statusCode = 302,
                $event = $this->createMockGetResponseEvent($request, true),
                $this->calling($event)->setResponse = function ($response) use ($statusCode, $url) {
                    $this
                        ->object($response)
                            ->isInstanceOf(RedirectResponse::class)
                        ->if($statusCodeResponse = $response->getStatusCode())
                        ->then
                            ->integer($statusCodeResponse)
                                ->isEqualTo($statusCode)
                        ->if($targetUrlResponse = $response->getTargetUrl())
                        ->then
                            ->string($targetUrlResponse)
                                ->isEqualTo($url);
                },
                $this->calling($request)->getClientIp = '128.0.0.1',
                $this->newTestedInstance($router, $comingSoonEnabled, $comingSoonAllowedIps)
            )
            ->if($this->testedInstance->onkernelRequest($event))
            ->then
                ->mock($event)
                    ->receive('setResponse')
                        ->once;
    }
}
