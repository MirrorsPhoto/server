Feature: Copy

	Background:
		Given that there is a "admin" role
		And that there is a registered user "admin" with "devpass" password
		And i am login as "admin" with "devpass" password

	Scenario: User tries to get copy price without token
		When i want get copy price format "A4"
		Then the resp status code should be 401
			And resp having error message:
				| auth.required |

	Scenario: User tries to get copy price unknown format
		Given i have token
		When i want get copy price format "A456"
		Then the resp status code should be 400
			And resp having error message:
				| copy.not_found |

	Scenario: User tries to get copy price without price
		Given that there is exist city "Халабудинск"
			And in this city exist department "Фуджия"
			And i work in "Фуджия" department
			And i have token
			And that there is a copies:
				| format |
				| A4     |
				| A5     |
		When i want get copy price format "A4"
		Then the resp status code should be 500
			And resp having error message:
				| copy.not_price |

	Scenario: User tries to get copy price
		Given that there is exist city "Халабудинск"
			And in this city exist department "Фуджия"
			And in this city exist department "Кодак"
			And i work in "Кодак" department
			And i have token
			And that there is a copies:
				| format | price | department |
				| A4     | 10    | Фуджия     |
				| A4     | 30    | Кодак      |
				| A5     | 15    | Кодак      |
		When i want get copy price format "A4"
		Then the resp status code should be 200
			And resp must contain numeric 30