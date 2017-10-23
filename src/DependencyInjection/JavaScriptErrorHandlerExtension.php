<?php

declare(strict_types = 1);

namespace Mhujer\JavaScriptErrorHandlerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class JavaScriptErrorHandlerExtension extends Extension
{

	/**
	 * Configures the passed container according to the merged configuration.
	 *
	 * @param mixed[] $configs
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 */
	public function load(array $configs, ContainerBuilder $container): void
	{
		$configuration = $this->getConfiguration($configs, $container);
		$config = $this->processConfiguration($configuration, $configs);

		$enabled = $config['enabled'];

		if ($enabled) {
			$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
			$loader->load('kernel_response_listener.yml');
		}
	}

	/**
	 * @param mixed[] $config An array of configuration values
	 * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
	 * @return \Mhujer\JavaScriptErrorHandlerBundle\DependencyInjection\Configuration
	 */
	public function getConfiguration(array $config, ContainerBuilder $container): Configuration
	{
		return new Configuration($container->getParameter('kernel.debug'));
	}

}
