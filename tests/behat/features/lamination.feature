Feature: Lamination

	Background:
		Given that there is a "admin" role
		And that there is a registered user "admin" with "devpass" password
		And i am login as "admin" with "devpass" password

	Scenario: User tries to get sizes without token
		When i want get lamination sizes
		Then the resp status code should be 401
			And resp having error message:
				| auth.required |

	Scenario: User tries to get sizes when there are none
		Given i have token
		When i want get lamination sizes
		Then the resp status code should be 500
			And resp having error message:
				| lamination.no_sizes |

	Scenario: User tries to get sizes without price
		Given that there is exist city "Халабудинск"
			And in this city exist department "Фуджия"
			And i work in "Фуджия" department
			And i have token
			And that there is a sizes:
				| format |
				| A4     |
				| A5     |
		When i want get lamination sizes
		Then the resp status code should be 500
			And resp having error message:
				| lamination.no_sizes |

	Scenario: User tries to get sizes
		Given that there is exist city "Халабудинск"
			And in this city exist department "Фуджия"
			And in this city exist department "Кодак"
			And i work in "Кодак" department
			And i have token
			And that there is a sizes:
				| format | price | department |
				| A4     | 10    | Фуджия     |
				| A4     | 30    | Кодак      |
				| A5     | 15    | Кодак      |
		When i want get lamination sizes
		Then the resp status code should be 200
		And resp must contain correct data
				"""
					[
						{
							"id": \d+,
							"format": "A4",
							"price": 30
						},
						{
							"id": \d+,
							"format": "A5",
							"price": 15
						}
					]
				"""