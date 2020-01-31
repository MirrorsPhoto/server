<?php

namespace Core;

use Apple\ApnPush\Certificate\Certificate;
use Apple\ApnPush\Model\Alert;
use Apple\ApnPush\Model\Aps;
use Apple\ApnPush\Model\DeviceToken;
use Apple\ApnPush\Model\Localized;
use Apple\ApnPush\Model\Notification;
use Apple\ApnPush\Model\Payload;
use Apple\ApnPush\Model\Priority;
use Apple\ApnPush\Model\PushType;
use Apple\ApnPush\Model\Receiver;
use Apple\ApnPush\Protocol\Http\Authenticator\CertificateAuthenticator;
use Apple\ApnPush\Protocol\ProtocolInterface;
use Apple\ApnPush\Sender\Builder\Http20Builder;
use Apple\ApnPush\Sender\Sender;
use Apple\ApnPush\Sender\SenderInterface;

class APN
{

	use Singleton;

	/**
	 * @var SenderInterface
	 */
	private $sender;

	/**
	 * @var ProtocolInterface
	 */
	private $protocol;

	public function __construct()
	{
		$certificate = new Certificate(__DIR__ . '/../' . 'apns-prod-cert.pem', $_ENV['APN_CERT_PHRASE']);
		$authenticator = new CertificateAuthenticator($certificate);
		$builder = new Http20Builder($authenticator);

		$this->protocol = $builder->buildProtocol();
		$this->sender = new Sender($this->protocol);
	}

	public function close()
	{
		$this->protocol->closeConnection();
	}

	public function send(string $receiver, $title, $body, $data = [], int $badge = null): void
	{
		if (is_array($title) && is_array($body)){
			$alert = new Alert();

			$alert->withLocalizedTitle(new Localized($title['key'], $title['args'] ?: []));
			$alert->withBodyLocalized(new Localized($body['key'], $body['args'] ?: []));
		} else {
			$alert = new Alert($body, $title);
		}

		$aps = new Aps($alert);
		if (!is_null($badge)) {
			$aps->withBadge($badge);
		}

		$payload = new Payload($aps, $data);

		$notification = (new Notification($payload))
			->withPriority(Priority::immediately())
			->withPushType(PushType::alert());

		$receiver = new Receiver(new DeviceToken($receiver), $_ENV['APPLE_CLIENT_ID']);

		$this->sender->send($receiver, $notification);
	}

}
