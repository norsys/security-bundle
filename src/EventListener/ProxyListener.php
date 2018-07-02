<?php
declare(strict_types=1);

namespace Norsys\SecurityBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * ProxyListener
 */
class ProxyListener
{
    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var string
     */
    private $envVariableName;

    /**
     * @var string
     */
    private $envVariableSeparator;

    /**
     * @var string
     */
    private $trustedHeaderSet;

    /**
     * @param boolean $enabled
     * @param string  $envVariableName
     * @param string  $envVariableSeparator
     * @param string  $trustedHeaderSet
     */
    public function __construct(
        bool $enabled,
        string $envVariableName = '',
        string $envVariableSeparator = ',',
        string $trustedHeaderSet = 'HEADER_X_FORWARDED_ALL'
    ) {
        $this->enabled              = $enabled;
        $this->envVariableName      = $envVariableName;
        $this->envVariableSeparator = $envVariableSeparator;
        $this->trustedHeaderSet     = $trustedHeaderSet;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->enabled === false || $event->isMasterRequest() === false) {
            return;
        }

        $request        = $event->getRequest();
        $trustedProxies = array_map('trim', explode($this->envVariableSeparator, getenv($this->envVariableName)));
        $trustedHeader = constant(Request::class.'::'.$this->trustedHeaderSet);
        $request->setTrustedProxies($trustedProxies, $trustedHeader);
    }
}
