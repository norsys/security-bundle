<?php
declare(strict_types = 1);

namespace Norsys\SecurityBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class ComingSoonListener
 */
class ComingSoonListener
{
    /**
     * Route name to coming soon page
     */
    const ROUTE_NAME_COMING_SOON = 'norsys_security_coming_soon';

    /**
     * @var Router
     */
    private $router;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var array
     */
    private $allowedIps;

    /**
     * @param Router  $router
     * @param boolean $enabled
     * @param array   $allowedIps
     */
    public function __construct(
        Router $router,
        bool $enabled,
        array $allowedIps
    ) {
        $this->router     = $router;
        $this->enabled    = $enabled;
        $this->allowedIps = $allowedIps;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->enabled === false || $event->isMasterRequest() === false) {
            return;
        }

        // No redirect if current page is coming soon
        try {
            $routerParameters = $this->router->matchRequest($event->getRequest());

            if ($routerParameters['_route'] === self::ROUTE_NAME_COMING_SOON) {
                return;
            }
        } catch (\Exception $exception) {
            // Redirect to coming soon to all errors
        }

        // No redirect allowed ips
        if (in_array($event->getRequest()->getClientIp(), $this->allowedIps) === true) {
            return;
        }

        $event->setResponse(
            new RedirectResponse(
                $this->router->generate(self::ROUTE_NAME_COMING_SOON),
                302
            )
        );
    }
}
