<?php
declare(strict_types = 1);

namespace Norsys\SecurityBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * HttpsRedirectListener
 */
class HttpsRedirectListener
{
    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @param boolean $enabled
     */
    public function __construct(bool $enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->enabled === false || $event->isMasterRequest() === false) {
            return;
        }

        $request = $event->getRequest();
        if ($request->getScheme() === 'http') {
            $redirectUrl = sprintf('https://%s%s', $request->getHttpHost(), $request->getPathInfo());
            $event->setResponse(new RedirectResponse($redirectUrl, 301));
        }
    }
}
