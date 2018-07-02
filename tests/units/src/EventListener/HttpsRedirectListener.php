<?php
declare(strict_types = 1);

namespace Norsys\SecurityBundle\Tests\Units\EventListener;

use Norsys\SecurityBundle\Tests\Units\Helpers\RequestHelper;
use Norsys\SecurityBundle\Tests\Units\Test;
use Symfony\Component\HttpFoundation\RedirectResponse;
use mock\Symfony\Component\HttpKernel\Event\GetResponseEvent as MockOfEvent;
use mock\Symfony\Component\HttpFoundation\Request as MockOfRequest;

class HttpsRedirectListener extends Test
{
    use RequestHelper;

    public function testOnRedirectIsDisabledAndOnMasterRequest()
    {
        $this
            ->assert('Https redirect is disabled.')
            ->given(
                $httpsRedirectEnabled = false,
                $event = new MockOfEvent,
                $this->newTestedInstance($httpsRedirectEnabled)
            )
            ->if($result = $this->testedInstance->onKernelRequest($event))
            ->then
                ->mock($event)
                    ->call('setResponse')
                        ->never;
    }

    public function testRedirectionWithHttpProxyEnabled()
    {
        $this
            ->assert('Http proxy is enabled.')
            ->given(
                $httpsRedirectEnabled = true,
                $event = new MockOfEvent,
                $statusCode = 301,
                $url = 'https://hostinfo',
                $this->calling($event)->setResponse = function($response) use ($statusCode, $url) {
                    /** @var RedirectResponse $response */
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
                $request = $this->createMockRequest(),
                $this->calling($event)->getRequest = $request,
                $this->newTestedInstance($httpsRedirectEnabled),
                $this->calling($request)->getScheme = 'http',
                $this->calling($request)->getHttpHost = 'host',
                $this->calling($request)->getPathInfo = 'info'
            )
            ->if($this->testedInstance->onKernelRequest($event))
            ->then
                ->mock($event)
                    ->call('setResponse')
                        ->once;
    }
}
