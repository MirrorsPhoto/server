<?php

class OtherContext extends AbstractContext
{

	/**
	 * @When i am open :subdomain subdomain
	 *
	 * @param string $subdomain
	 */
	public function openSubdomain(string $subdomain): void
	{
		$url = "http://$subdomain.{$_ENV['DOMAIN']}";

		$response = $this->request($url);

		$this->data['response'] = $response;
	}

}
