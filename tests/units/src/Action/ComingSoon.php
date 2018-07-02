<?php
declare(strict_types = 1);

namespace Norsys\SecurityBundle\Tests\Units\Action;

use Norsys\SecurityBundle\Tests\Units\Test;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \mock\Symfony\Component\Templating\EngineInterface as MockOfTemplateEngine;

class ComingSoon extends Test
{
    public function testOnComingSoonIsEnabled()
    {
        $this
            ->assert('Coming soon is enabled.')
            ->given(
                $templateEngine = new MockOfTemplateEngine,
                $comingSoonEnabled = true,
                $comingSoonTemplate = 'template.html.twig',
                $this->newTestedInstance($templateEngine, $comingSoonEnabled, $comingSoonTemplate)
            )
            ->if($this->testedInstance->__invoke())
            ->then
                ->mock($templateEngine)
                    ->call('render')
                        ->withArguments('template.html.twig')
                            ->once();
    }

    public function testOnComingSoonIsDisabled()
    {
        $this
            ->assert('Coming soon is disabled')
            ->given(
                $templateEngine = new MockOfTemplateEngine,
                $comingSoonEnabled = false,
                $comingSoonTemplate = 'template.html.twig',
                $this->newTestedInstance($templateEngine, $comingSoonEnabled, $comingSoonTemplate)
            )
            ->exception(
                function() {
                    $this->testedInstance->__invoke();
                })->isInstanceOf(NotFoundHttpException::class);
    }
}
