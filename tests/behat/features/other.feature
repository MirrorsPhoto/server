Feature: Other

	Scenario: User open diff subdomen
		When i am open "other" subdomain
		Then the resp status code should be 404