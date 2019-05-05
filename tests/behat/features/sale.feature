Feature: Sale

	Background:
		Given that there is a "admin" role
		And that there is a registered user "admin" with "devpass" password
		And i am login as "admin" with "devpass" password

	Scenario: User tries to sale without token
		When i want sale:
			| type  | id | copies |
			| photo | 1  | 1      |
		Then the resp status code should be 401
		And resp having error message:
			| auth.required |

	Scenario: User tries to sale empty items
		Given i have token
		When i want sale:
			| type  | id | copies |
		Then the resp status code should be 400
		And resp having error message:
			| sale.invalid_items |

	Scenario: User tries to sale unknown type
		Given i have token
		When i want sale:
			| type  | id | copies |
			| other | 1  | 1      |
		Then the resp status code should be 400
		And resp having error message:
			| sale.unknown_type |

	Scenario: User tries to sale zero copies
		Given i have token
		When i want sale:
			| type  | id | copies |
			| photo | 1  | 0      |
		Then the resp status code should be 400
		And resp having error message:
			| sale.invalid_copies |

	Scenario: User tries to sale copies as string
		Given i have token
		When i want sale:
			| type  | id | copies |
			| photo | 1  | ahgjsd |
		Then the resp status code should be 400
		And resp having error message:
			| sale.invalid_copies |

	Scenario: User tries to sale zero id
		Given i have token
		When i want sale:
			| type  | id | copies |
			| photo | 0  | 1      |
		Then the resp status code should be 400
		And resp having error message:
			| sale.invalid_id |

	Scenario: User tries to sale id as string
		Given i have token
		When i want sale:
			| type  | id | copies |
			| photo | a  | 1      |
		Then the resp status code should be 400
		And resp having error message:
			| sale.invalid_id |

	Scenario: User tries to sale wrong id
		Given i have token
		When i want sale:
			| type  | id | copies |
			| photo | 9  | 1      |
		Then the resp status code should be 400
		And resp having error message:
			| sale.wrong_id |