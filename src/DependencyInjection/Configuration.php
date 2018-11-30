<?php

declare(strict_types = 1);

namespace Mhujer\JavaScriptErrorHandlerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

	/** @var bool */
	private $enabledDefaultValue;

	public function __construct(
		bool $enabledDefaultValue
	)
	{
		$this->enabledDefaultValue = $enabledDefaultValue;
	}

	public function getConfigTreeBuilder(): TreeBuilder
	{
		$treeBuilder = new TreeBuilder('java_script_error_handler');
		$rootNode = $treeBuilder->getRootNode();

		// @codingStandardsIgnoreStart tree is indented for better readability
		$rootNode
			->children()
				->booleanNode('enabled')->defaultValue($this->enabledDefaultValue)->end()
			->end();
		// @codingStandardsIgnoreEnd

		return $treeBuilder;
	}

}
