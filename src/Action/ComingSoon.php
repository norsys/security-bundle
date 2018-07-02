<?php
declare(strict_types = 1);

namespace Norsys\SecurityBundle\Action;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class ComingSoon
 */
class ComingSoon
{
    /**
     * @var EngineInterface
     */
    private $templateEngine;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var string
     */
    private $template;

    /**
     * @param EngineInterface $templateEngine
     * @param boolean         $enabled
     * @param string          $template
     */
    public function __construct(
        EngineInterface $templateEngine,
        bool $enabled,
        string $template
    ) {
        $this->templateEngine = $templateEngine;
        $this->enabled        = $enabled;
        $this->template       = $template;
    }

    /**
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function __invoke() : Response
    {
        if ($this->enabled === false) {
            throw new NotFoundHttpException();
        }

        return new Response($this->templateEngine->render($this->template));
    }
}
