Feature: Photo

	Background:
		Given that there is a "admin" role
		And that there is a registered user "admin" with "devpass" password
		And i am login as "admin" with "devpass" password

	Scenario: User tries to get sizes without token
		When i want get photo sizes
		Then the resp status code should be 401
			And resp having error message:
				| auth.required |

	Scenario: User tries to get sizes when there are none
		Given i have token
		When i want get photo sizes
		Then the resp status code should be 500
			And resp having error message:
				| photo.no_sizes |

	Scenario: User tries to get sizes
		Given that there is exist city "Халабудинск"
			And in this city exist department "Фуджия"
			And in this city exist department "Кодак"
			And i work in "Фуджия" department
			And i have token
			And that there is a sizes:
				| width | height | count | price | department |
				| 3     | 4      | 4     | 100   | Фуджия     |
				| 3     | 4      | 6     | 120   | Фуджия     |
				| 5     | 5      | 2     | 110   | Фуджия     |
				| 5     | 5      | 2     | 910   | Кодак      |
		When i want get photo sizes
		Then the resp status code should be 200
			And resp must contain correct data
				"""
					[
						{
							"width": 3,
							"height": 4,
							"variations": [
								{
									"id": \d+,
									"count": 4,
									"price": 100
								},
								{
									"id": \d+,
									"count": 6,
									"price": 120
								}
							]
						},
						{
							"width": 5,
							"height": 5,
							"variations": [
								{
									"id": \d+,
									"count": 2,
									"price": 110
								}
							]
						}
					]
				"""