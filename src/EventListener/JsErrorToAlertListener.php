<?php declare(strict_types = 1);

namespace Mhujer\JavaScriptErrorHandlerBundle\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class JsErrorToAlertListener implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{

	public function onKernelResponse(ResponseEvent $event): void
	{
		$response = $event->getResponse();
		$request = $event->getRequest();

		if (!$event->isMainRequest()) {
			return;
		}

		if ($request->isXmlHttpRequest()) {
			return;
		}

		if ($response->isRedirection()) {
			return;
		}

		if ($response->headers->has('Content-Type')) {
			/** @var string $contentTypeHeader */
			$contentTypeHeader = $response->headers->get('Content-Type');
			if (strpos($contentTypeHeader, 'html') === false) {
				return;
			}
		}

		if ($request->getRequestFormat() !== 'html') {
			return;
		}

		if ($response->headers->has('Content-Disposition')) {
			/** @var string $contentDispositionHeader */
			$contentDispositionHeader = $response->headers->get('Content-Disposition');
			if (stripos($contentDispositionHeader, 'attachment;') !== false) {
				return;
			}
		}

		$this->injectScript($response, $request);
	}

	protected function injectScript(Response $response, Request $request): void
	{
		$content = $response->getContent();
		if ($content === false) {
			return;
		}

		$pos = stripos($content, '<head>');

		if ($pos !== false) {
			$toolbar = "\n\n" . '
	<script type="text/javascript" id="mhujer-javascript-error-handler-bundle">
		window.onerror = function (errorMessage, url, line, col, error) {
			alert(
				errorMessage + "\n\n" +
				error.stack
			);
		};
	</script>
' . "\n";
			$content = substr($content, 0, $pos + 6) . $toolbar . substr($content, $pos + 6);
			$response->setContent($content);
		}
	}

	/**
	 * @return mixed[]
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			KernelEvents::RESPONSE => ['onKernelResponse', -200],
		];
	}

}
