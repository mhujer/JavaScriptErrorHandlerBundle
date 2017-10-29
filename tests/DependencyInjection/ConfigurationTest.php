<?php

declare(strict_types = 1);

namespace Mhujer\JavaScriptErrorHandlerBundle\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationTest extends TestCase
{

	use ConfigurationTestCaseTrait;

	public function testEmptyConfigurationIsValid(): void
	{
		$this->assertConfigurationIsValid(
			[
				[], // no values at all
			]
		);
	}

	public function testEnabledConfigurationIsValid(): void
	{
		$this->assertConfigurationIsValid(
			[
				[
					'enabled' => true,
				],
			]
		);
	}

	public function testDisabledConfigurationIsValid(): void
	{
		$this->assertConfigurationIsValid(
			[
				[
					'enabled' => false,
				],
			]
		);
	}

	public function testEnabledConfigurationIsValidXX(): void
	{
		$this->assertConfigurationIsInvalid(
			[
				[
					'enabled' => 1,
				],
			],
			'Invalid type for path "java_script_error_handler.enabled". Expected boolean, but got integer.'
		);
	}

	public function testInvalidConfigurationIsInvalid(): void
	{
		$this->assertConfigurationIsInvalid(
			[
				[
					'invalid_option' => 1,
				],
			],
			'Unrecognized option "invalid_option" under "java_script_error_handler"'
		);
	}

	protected function getConfiguration(): ConfigurationInterface
	{
		return new Configuration(true);
	}

}
