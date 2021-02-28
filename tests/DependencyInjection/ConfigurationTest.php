<?php declare(strict_types = 1);

namespace Mhujer\JavaScriptErrorHandlerBundle\DependencyInjection;

use PackageVersions\Versions;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{

	use \Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;

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

	public function testEnabledConfigurationInvalidType(): void
	{
		// there are different messages for Symfony 4.4 and 5.1, @see https://github.com/symfony/symfony/pull/35945

		// symfony/config 4.4
		if (preg_match('~^v4\.4~', Versions::getVersion('symfony/config')) === 1) {
			$this->assertConfigurationIsInvalid(
				[
					[
						'enabled' => 1,
					],
				],
				'Invalid type for path "java_script_error_handler.enabled". Expected boolean, but got integer.'
			);
		} else {
			$this->assertConfigurationIsInvalid(
				[
					[
						'enabled' => 1,
					],
				],
				'Invalid type for path "java_script_error_handler.enabled". Expected "bool", but got "int".'
			);
		}
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
