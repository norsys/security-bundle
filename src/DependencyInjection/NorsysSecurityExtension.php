<?php

namespace Norsys\SecurityBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class NorsysSecurityExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('norsys_security.coming_soon.enabled', $config['coming_soon']['enabled']);
        $container->setParameter('norsys_security.coming_soon.allowed_ips', $config['coming_soon']['allowed_ips']);
        $container->setParameter('norsys_security.coming_soon.template', $config['coming_soon']['template']);
        $container->setParameter('norsys_security.https_redirect.enabled', $config['https_redirect']['enabled']);
        $container->setParameter('norsys_security.proxy.enabled', $config['proxy']['enabled']);
        $container->setParameter('norsys_security.proxy.env_variable_name', $config['proxy']['env_variable_name']);
        $container->setParameter('norsys_security.proxy.trusted_header_set', $config['proxy']['trusted_header_set']);
        $container->setParameter(
            'norsys_security.proxy.env_variable_separator',
            $config['proxy']['env_variable_separator']
        );

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
