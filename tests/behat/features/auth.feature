Feature: Authorization

	Background:
		Given that there is a "admin" role
			And that there is a registered user "admin" with "devpass" password

	Scenario: User tries to login with valid credential
		When i am login as "admin" with "devpass" password
		Then the resp status code should be 200
			And the "Content-Type" header should be "application/json; charset=UTF-8"
			And i have token
			And token must contain valid JWT
			And JWT token payload must contain:
				| id | username | first_name | middle_name | last_name | email | role_id | role_phrase | avatar |

	Scenario: User tries to login with wrong password
		When i am login as "admin" with "other" password
		Then the resp status code should be 400
			And the "Content-Type" header should be "application/json; charset=UTF-8"
			And resp having error message:
				| auth.invalid_login_or_pass |

	Scenario: User tries to login with wrong username
		When i am login as "other" with "devpass" password
		Then the resp status code should be 400
			And the "Content-Type" header should be "application/json; charset=UTF-8"
			And resp having error message:
				| auth.invalid_login_or_pass |