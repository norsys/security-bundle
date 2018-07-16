<?php
declare(strict_types = 1);

namespace Norsys\SecurityBundle\Tests\Units\EventListener;

use Norsys\SecurityBundle\Tests\Units\Test;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class ProxyListener
 *
 * @package Norsys\SecurityBundle\Tests\Units\EventListener
 */
class ProxyListener extends Test
{
    /**
     * TestOnKernelRequestDisabled
     */
    public function testOnKernelRequestDisabled()
    {
        $this
            ->given(
                $this->newTestedInstance(false),
                $event = $this->newMockInstance(GetResponseEvent::class)
            )
            ->if($this->testedInstance->onKernelRequest($event))
            ->then()
            ->mock($event)->wasNotCalled();
    }

    /**
     * TestOnKernelRequest
     */
    public function testOnKernelRequest()
    {
        $this
            ->given(
                $this->newTestedInstance(true, 'ENV_VARIABLE_NAME', ',', 'HEADER_X_FORWARDED_ALL'),
                $request = $this->newMockInstance(Request::class),
                $event = $this->newMockInstance(GetResponseEvent::class),
                $this->calling($event)->getRequest = $request,
                $this->function->getenv = '192.168.0.1, 127.0.0.1'
            )
            ->if($this->testedInstance->onKernelRequest($event))
            ->then()
            ->array($request->getTrustedProxies())->isEqualTo(['192.168.0.1', '127.0.0.1'])
            ->integer($request->getTrustedHeaderSet())->isEqualTo(0b11110);
    }
}
