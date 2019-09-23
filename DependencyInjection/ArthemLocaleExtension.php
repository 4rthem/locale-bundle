<?php

namespace Arthem\Bundle\LocaleBundle\DependencyInjection;

use Arthem\Bundle\LocaleBundle\Consumer\EventConsumer;
use Arthem\Bundle\LocaleBundle\Consumer\FailedEventConsumer;
use Arthem\Bundle\LocaleBundle\Model\FailedEventManager;
use Arthem\Bundle\LocaleBundle\Producer\Adapter\AMQPProducerAdapter;
use Arthem\Bundle\LocaleBundle\Producer\Adapter\DirectProducerAdapter;
use Arthem\Bundle\LocaleBundle\Producer\Adapter\EventProducerAdapterInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use LogicException;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ArthemLocaleExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('arthem.locale.locales', $config['locales']);
    }
}
