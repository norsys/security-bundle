<?php

namespace Norsys\SecurityBundle\DependencyInjection;

use function preg_match;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('norsys_security');

        $rootNode
            ->children()
                ->arrayNode('https_redirect')
                    ->info('Optional https redirect configuration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->info('Enable force redirect to https')
                            ->defaultValue(false)
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('proxy')
                    ->info('Optional proxy configuration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->info('Enable proxy')
                            ->defaultValue(false)
                        ->end()
                        ->scalarNode('env_variable_name')
                            ->info('Configure name to environment variable list trusted proxies')
                            ->defaultValue('TRUSTED_PROXIES')
                        ->end()
                        ->scalarNode('env_variable_separator')
                            ->info('Configure proxy separator in environment variable listed trusted proxies')
                            ->defaultValue(',')
                        ->end()
                        ->scalarNode('trusted_header_set')
                            ->info('A bit field of Request::HEADER_*, to set which headers to trust from your proxies')
                            ->defaultValue(Request::HEADER_X_FORWARDED_ALL)
                            ->validate()
                                ->ifEmpty()
                                ->then(function () {
                                    return Request::HEADER_X_FORWARDED_ALL;
                                })
                            ->end()
                            ->validate()
                                ->ifTrue(
                                    function (string $header) {
                                        if (preg_match('/^HEADER_FORWARDED$|^HEADER_X_[\w]+$/', $header) === 0
                                            || defined(Request::class . '::' . $header) === false) {
                                            return true;
                                        }
                                    }
                                )
                                ->thenInvalid('Invalid header to trust from your proxies %s')
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('coming_soon')
                    ->info('Optional coming soon configuration')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->info('Redirect all requests to coming soon page')
                            ->defaultValue(false)
                        ->end()
                        ->scalarNode('template')
                            ->info('Template to display coming soon page')
                            ->defaultValue('NorsysSecurityBundle::coming_soon.html.twig')
                        ->end()
                        ->arrayNode('allowed_ips')
                            ->prototype('scalar')
                            ->end()
                            ->defaultValue([])
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
