Feature: Photo

	Background:
		Given that there is a "admin" role
		And that there is a registered user "admin" with "devpass" password
		And i am login as "admin" with "devpass" password

	Scenario: User tries to get sizes without token
		When i want get photo sizes
		Then the response status code should be 401
			And response having error message:
				| auth.required |

	Scenario: User tries to get sizes when there are none
		Given i have token
		When i want get photo sizes
		Then the response status code should be 500
			And response having error message:
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
		Then the response status code should be 200