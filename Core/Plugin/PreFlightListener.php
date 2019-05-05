<?php

namespace Core\Plugin;

use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;

/**
 * Class PreFlightListener
 *
 * @package RealWorld\Listener
 * @property Request $request
 * @property Response $response
 */
class PreFlightListener extends Plugin
{
	// @codingStandardsIgnoreLine SlevomatCodingStandard.Variables.UnusedVariable
	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher): void
	{
		$di = $dispatcher->getDI();

		/** @var Request $request */
		$request = $di->get('request');

		/** @var Response $response */
		$response = $di->get('response');

		if ($this->isCorsRequest($request)) {
			$response
				->setHeader('Access-Control-Allow-Origin', $this->getOrigin($request))
				->setHeader('Access-Control-Allow-Methods', 'GET, POST, PATCH')
				->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
				->setHeader('Access-Control-Allow-Credentials', 'true');
		}

		if ($this->isPreflightRequest($request)) {
			$response->setStatusCode(200, 'OK')->send();
			exit;
		}
	}

	public function isCorsRequest(Request $request): bool
	{
		return !empty($request->getHeader('Origin')) && !$this->isSameHost($request);
	}

	public function isPreflightRequest(Request $request): bool
	{
		return $this->isCorsRequest($request)
			&& $request->getMethod() === 'OPTIONS'
			&& !empty($request->getHeader('Access-Control-Request-Method'));
	}

	public function isSameHost(Request $request): bool
	{
		return $request->getHeader('Origin') === $this->getSchemeAndHttpHost($request);
	}

	public function getSchemeAndHttpHost(Request $request): string
	{
		return $request->getScheme() . '://' . $request->getHttpHost();
	}

	public function getOrigin(Request $request): string
	{
		return $request->getHeader('Origin') ? $request->getHeader('Origin') : '*';
	}
}
