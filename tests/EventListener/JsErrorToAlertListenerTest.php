<?php

declare(strict_types = 1);

namespace Mhujer\JavaScriptErrorHandlerBundle\EventListener;

use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelEvents;

class JsErrorToAlertListenerTest extends TestCase
{

	public function testIsSubscribedToKernelResponse(): void
	{
		$subscribedEvents = JsErrorToAlertListener::getSubscribedEvents();

		$this->assertArrayHasKey(KernelEvents::RESPONSE, $subscribedEvents);
	}

	/**
	 * @dataProvider htmlScriptDataProvider
	 * @param string $responseBody
	 * @param string $expectedResponse
	 */
	public function testInjectScript(string $responseBody, string $expectedResponse): void
	{
		$listener = new JsErrorToAlertListener();
		$injectScriptReflection = new ReflectionMethod($listener, 'injectScript');
		$injectScriptReflection->setAccessible(true);

		$response = new Response($responseBody);

		$injectScriptReflection->invoke($listener, $response, Request::create('/'));
		$this->assertSame($expectedResponse, $response->getContent());
	}


	/**
	 * @dataProvider htmlScriptDataProvider
	 * @param string $responseBody
	 * @param string $expectedResponse
	 */
	public function testScriptIsInjected(string $responseBody, string $expectedResponse): void
	{
		$response = new Response($responseBody);

		$event = new FilterResponseEvent(
			$this->getKernelMock(),
			$this->getRequestMock(),
			HttpKernelInterface::MASTER_REQUEST,
			$response
		);

		$listener = new JsErrorToAlertListener();
		$listener->onKernelResponse($event);

		$this->assertSame(
			$expectedResponse,
			$response->getContent()
		);
	}

	private const BASIC_HTML = '<html><head></head><body></body></html>';
	private const BASIC_HTML_WITH_JS = '<html><head>


	<script type="text/javascript" id="mhujer-javascript-error-handler-bundle">
		window.onerror = function (errorMessage, url, line, col, error) {
			alert(
				errorMessage + "\n\n" +
				error.stack
			);
		};
	</script>

</head><body></body></html>';

	/**
	 * @return string[][]
	 */
	public function htmlScriptDataProvider(): array
	{
		return [
			'without head tag' => [
				'<html><body></body></html>',
				'<html><body></body></html>',
			],
			'with head tag' => [
				self::BASIC_HTML,
				self::BASIC_HTML_WITH_JS,
			],
		];
	}

	/**
	 * @dataProvider redirectCodesDataProvider
	 * @param int $statusCode
	 */
	public function testScriptIsNotInjectedOnRedirection(int $statusCode): void
	{
		$response = new Response(self::BASIC_HTML, $statusCode);

		$event = new FilterResponseEvent(
			$this->getKernelMock(),
			$this->getRequestMock(),
			HttpKernelInterface::MASTER_REQUEST,
			$response
		);

		$listener = new JsErrorToAlertListener();
		$listener->onKernelResponse($event);

		$this->assertSame(
			self::BASIC_HTML,
			$response->getContent()
		);
	}

	/**
	 * @return int[][]
	 */
	public function redirectCodesDataProvider(): array
	{
		return [
			[301],
			[302],
		];
	}

	public function testScriptIsNotInjectedOnSubRequest(): void
	{
		$response = new Response(self::BASIC_HTML);

		$event = new FilterResponseEvent(
			$this->getKernelMock(),
			$this->getRequestMock(),
			HttpKernelInterface::SUB_REQUEST,
			$response
		);

		$listener = new JsErrorToAlertListener();
		$listener->onKernelResponse($event);

		$this->assertSame(
			self::BASIC_HTML,
			$response->getContent()
		);
	}

	public function testScriptIsNotInjectedOnIncompleteHtmlResponses(): void
	{
		$response = new Response('<div>Some content</div>');

		$event = new FilterResponseEvent(
			$this->getKernelMock(),
			$this->getRequestMock(),
			HttpKernelInterface::MASTER_REQUEST,
			$response
		);

		$listener = new JsErrorToAlertListener();
		$listener->onKernelResponse($event);

		$this->assertSame(
			'<div>Some content</div>',
			$response->getContent()
		);
	}

	public function testScriptIsNotInjectedOnXmlHttpRequests(): void
	{
		$response = new Response(self::BASIC_HTML);

		$event = new FilterResponseEvent(
			$this->getKernelMock(),
			$this->getRequestMock(true),
			HttpKernelInterface::MASTER_REQUEST,
			$response
		);

		$listener = new JsErrorToAlertListener();
		$listener->onKernelResponse($event);

		$this->assertSame(
			self::BASIC_HTML,
			$response->getContent()
		);
	}

	public function testScriptIsNotInjectedOnNonHtmlRequests(): void
	{
		$response = new Response(self::BASIC_HTML);

		$event = new FilterResponseEvent(
			$this->getKernelMock(),
			$this->getRequestMock(false, 'json'),
			HttpKernelInterface::MASTER_REQUEST,
			$response
		);

		$listener = new JsErrorToAlertListener();
		$listener->onKernelResponse($event);

		$this->assertSame(
			self::BASIC_HTML,
			$response->getContent()
		);
	}

	public function testScriptIsNotInjectedOnContentDispositionAttachment(): void
	{
		$response = new Response(self::BASIC_HTML);
		$response->headers->set('Content-Disposition', 'attachment; filename=test.html');

		$event = new FilterResponseEvent(
			$this->getKernelMock(),
			$this->getRequestMock(),
			HttpKernelInterface::MASTER_REQUEST,
			$response
		);

		$listener = new JsErrorToAlertListener();
		$listener->onKernelResponse($event);

		$this->assertSame(
			self::BASIC_HTML,
			$response->getContent()
		);
	}

	public function testScriptIsNotInjectedOnNonHtmlContentType(): void
	{
		$response = new Response(self::BASIC_HTML);
		$response->headers->set('Content-Type', 'text/xml');

		$event = new FilterResponseEvent(
			$this->getKernelMock(),
			$this->getRequestMock(),
			HttpKernelInterface::MASTER_REQUEST,
			$response
		);

		$listener = new JsErrorToAlertListener();
		$listener->onKernelResponse($event);

		$this->assertSame(
			self::BASIC_HTML,
			$response->getContent()
		);
	}

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpKernel\Kernel
	 */
	protected function getKernelMock()
	{
		/** @var \Symfony\Component\HttpKernel\Kernel|\PHPUnit_Framework_MockObject_MockObject $kernelMock */
		$kernelMock = $this
			->getMockBuilder(Kernel::class)
			->disableOriginalConstructor()
			->getMock();
		return $kernelMock;
	}

	/**
	 * @param bool $isXmlHttpRequest
	 * @param string $requestFormat
	 * @return \PHPUnit_Framework_MockObject_MockObject|\Symfony\Component\HttpFoundation\Request
	 */
	protected function getRequestMock(bool $isXmlHttpRequest = false, string $requestFormat = 'html')
	{
		/** @var \Symfony\Component\HttpFoundation\Request|\PHPUnit_Framework_MockObject_MockObject $request */
		$request = $this
			->getMockBuilder(Request::class)
			->setMethods([
				'isXmlHttpRequest',
				'getRequestFormat',
			])
			->disableOriginalConstructor()
			->getMock();

		$request->expects($this->any())
			->method('isXmlHttpRequest')
			->will($this->returnValue($isXmlHttpRequest));

		$request->expects($this->any())
			->method('getRequestFormat')
			->will($this->returnValue($requestFormat));

		$request->headers = new HeaderBag();

		return $request;
	}

}
