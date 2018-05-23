<?php


use Phinx\Migration\AbstractMigration;

class TimeZone extends AbstractMigration
{
    public function change()
    {
			$this->execute("set timezone TO 'Europe/Moscow';");

			$this->execute('UPDATE "check" SET datetime = datetime + interval' . "'3 hour';");

			$this->execute("
				UPDATE copy SET datetime_create = datetime_create + interval '3 hour';
				UPDATE copy_price_history SET datetime_from = datetime_from + interval '3 hour';
				UPDATE copy_price_history SET datetime_to = datetime_to + interval '3 hour';
				UPDATE copy_sale SET datetime = datetime + interval '3 hour';
				UPDATE department_personnel_history SET datetime_from = datetime_from + interval '3 hour';
				UPDATE department_personnel_history SET datetime_to = datetime_to + interval '3 hour';
				UPDATE file SET datetime_create = datetime_create + interval '3 hour';
				UPDATE good SET datetime_сreate = datetime_сreate + interval '3 hour';
				UPDATE good_price_history SET datetime_from = datetime_from + interval '3 hour';
				UPDATE good_price_history SET datetime_to = datetime_to + interval '3 hour';
				UPDATE good_receipt SET datetime = datetime + interval '3 hour';
				UPDATE good_sale SET datetime = datetime + interval '3 hour';
				UPDATE lamination SET datetime_create = datetime_create + interval '3 hour';
				UPDATE lamination_price_history SET datetime_from = datetime_from + interval '3 hour';
				UPDATE lamination_price_history SET datetime_to = datetime_to + interval '3 hour';
				UPDATE lamination_sale SET datetime = datetime + interval '3 hour';
				UPDATE photo SET datetime_create = datetime_create + interval '3 hour';
				UPDATE photo_size SET datetime_create = datetime_create + interval '3 hour';
				UPDATE photo_price_history SET datetime_from = datetime_from + interval '3 hour';
				UPDATE photo_price_history SET datetime_to = datetime_to + interval '3 hour';
				UPDATE photo_sale SET datetime = datetime + interval '3 hour';
				UPDATE printing SET datetime_create = datetime_create + interval '3 hour';
				UPDATE printing_price_history SET datetime_from = datetime_from + interval '3 hour';
				UPDATE printing_price_history SET datetime_to = datetime_to + interval '3 hour';
				UPDATE printing_sale SET datetime = datetime + interval '3 hour';
				UPDATE service SET datetime_create = datetime_create + interval '3 hour';
				UPDATE service_price_history SET datetime_from = datetime_from + interval '3 hour';
				UPDATE service_price_history SET datetime_to = datetime_to + interval '3 hour';
				UPDATE service_sale SET datetime = datetime + interval '3 hour';
			");
		}
}
