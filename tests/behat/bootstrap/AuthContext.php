<?php

use Behat\Gherkin\Node\TableNode;
use Firebase\JWT\JWT;

class AuthContext extends AbstractContext
{
	/**
	 * @Then token must contain valid JWT
	 */
	public function checkJwt(): void
	{
		$response = $this->request('auth/check', 'GET');
		$responseCode = $response['status'];
		$responseBody = $response['body'];

		self::assertArrayHasKey('status', $responseBody);
		$responseStatus = $responseBody['status'];

		self::assertEquals($responseStatus, 'OK');
		self::assertEquals($responseCode, 200);
	}

	/**
	 * @param TableNode $table
	 *
	 * @Then JWT token payload must contain:
	 */
	public function checkJwtPayload(TableNode $table): void
	{
		$payload = JWT::decode($this->token, $_ENV['JWT_KEY'], ['HS256']);

		foreach ($table->getRow(0) as $key) {
			self::assertObjectHasAttribute($key, $payload);

			self::assertEquals($this->data['user'][$key], $payload->{$key});
		}
	}
}
