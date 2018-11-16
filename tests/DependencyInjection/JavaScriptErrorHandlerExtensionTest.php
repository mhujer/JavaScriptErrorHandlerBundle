<?php

declare(strict_types = 1);

namespace Mhujer\JavaScriptErrorHandlerBundle\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Mhujer\JavaScriptErrorHandlerBundle\EventListener\JsErrorToAlertListener;

class JavaScriptErrorHandlerExtensionTest extends AbstractExtensionTestCase
{

	private const LISTENER_CLASS_NAME = JsErrorToAlertListener::class;

	/**
	 * @return \Symfony\Component\DependencyInjection\Extension\Extension[]
	 */
	protected function getContainerExtensions(): array
	{
		return [
			new JavaScriptErrorHandlerExtension(),
		];
	}

	public function testListenerIsRegisteredInDebugMode(): void
	{
		$this->container->setParameter('kernel.debug', true);

		$this->load();

		$this->assertContainerBuilderHasService(self::LISTENER_CLASS_NAME);
	}

	public function testListenerIsNotRegisteredWithoutDebugMode(): void
	{
		$this->container->setParameter('kernel.debug', false);

		$this->load();

		$this->assertContainerBuilderNotHasService(self::LISTENER_CLASS_NAME);
	}

	public function testKernelDebugCanBeOverriddenToDisable(): void
	{
		$this->container->setParameter('kernel.debug', true);

		$this->load([
			'enabled' => false,
		]);

		$this->assertContainerBuilderNotHasService(self::LISTENER_CLASS_NAME);
	}

	public function testKernelDebugCanBeOverriddenToEnable(): void
	{
		$this->container->setParameter('kernel.debug', false);

		$this->load([
			'enabled' => true,
		]);

		$this->assertContainerBuilderHasService(self::LISTENER_CLASS_NAME);
	}

}
