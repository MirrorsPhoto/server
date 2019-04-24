<?php

use Behat\Gherkin\Node\TableNode;
use Firebase\JWT\JWT;
use PHPUnit\Framework\Constraint\IsType;

class AuthContext extends AbstractContext
{
	/**
	 * @Then token must contain valid JWT
	 */
	public function checkJWT()
	{
		$response = $this->request('auth/check', 'GET');
		$responseCode = $response['status'];
		$responseBody = $response['body'];

		self::assertObjectHasAttribute('status', $responseBody);
		$responseStatus = $responseBody->status;

		self::assertEquals($responseStatus, 'OK');
		self::assertEquals($responseCode, 200);
	}

	/**
	 * @Then JWT token payload must contain:
	 * @param TableNode $table
	 */
	public function checkJWTpayload(TableNode $table)
	{
		$payload = JWT::decode($this->token, $_ENV['JWT_KEY'], ['HS256']);

		foreach ($table->getRow(0) as $key) {
			self::assertObjectHasAttribute($key, $payload);

			switch ($key) {
				/** @noinspection PhpMissingBreakStatementInspection */
				case 'id':
					self::assertInternalType(IsType::TYPE_INT, $payload->{$key});
				default:
					self::assertEquals($this->data['user'][$key], $payload->{$key});
			}
		}
	}
}
