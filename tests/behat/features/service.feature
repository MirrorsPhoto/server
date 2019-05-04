Feature: Service

	Background:
		Given that there is a "admin" role
		And that there is a registered user "admin" with "devpass" password
		And i am login as "admin" with "devpass" password

	Scenario: User tries to get service list without token
		When i want get service list
		Then the resp status code should be 401
			And resp having error message:
				| auth.required |

	Scenario: User tries to get service list when there are none
		Given i have token
		When i want get service list
		Then the resp status code should be 500
			And resp having error message:
				| service.empty |

	Scenario: User tries to get service list without price
		Given that there is exist city "Халабудинск"
			And in this city exist department "Фуджия"
			And i work in "Фуджия" department
			And i have token
			And that there is a services:
				| name |
				| Burn |
				| Scan |
		When i want get service list
		Then the resp status code should be 500
			And resp having error message:
				| service.empty |

	Scenario: User tries to get service list
		Given that there is exist city "Халабудинск"
			And in this city exist department "Фуджия"
			And in this city exist department "Кодак"
			And i work in "Кодак" department
			And i have token
			And that there is a services:
				| name | price | department |
				| Burn | 10    | Фуджия     |
				| Burn | 30    | Кодак      |
				| Scan | 15    | Кодак      |
		When i want get service list
		Then the resp status code should be 200
			And resp must contain correct data
				"""
					[
						{
							"id": \d+,
							"name": "Burn",
							"price": 30
						},
						{
							"id": \d+,
							"name": "Scan",
							"price": 15
						}
					]
				"""