<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Domain\Criteria;
	
	use App\Shared\Domain\Criteria\Criteria;
	use App\Shared\Domain\Criteria\Filters;
	use App\Shared\Domain\Criteria\Order;
	use App\Tests\Shared\Domain\IntegerMother;
	
	final class CriteriaMother
	{
		public static function empty(): Criteria
		{
			return self::create(FiltersMother::blank(), OrderMother::none());
		}
		
		public static function create(
			Filters $filters,
			Order $order = null,
			int $offset = null,
			int $limit = null
		): Criteria {
			return new Criteria($filters, $order ?: OrderMother::none(), $offset, $limit);
		}
		
		public static function random(): Criteria
		{
			return self::create(
				FiltersMother::random(),
				OrderMother::random(),
				IntegerMother::random(),
				IntegerMother::random()
			);
		}
		
		public static function contains(string $field, string $value): Criteria
		{
			return CriteriaMother::create(
				FiltersMother::createOne(
					FilterMother::fromValues(
						[
							'field' => $field,
							'operator' => 'CONTAINS',
							'value' => $value,
						]
					)
				)
			);
		}
		
		public static function equals(string $field, $value): Criteria
		{
			return CriteriaMother::create(
				FiltersMother::createOne(
					FilterMother::fromValues(
						[
							'field' => $field,
							'operator' => '=',
							'value' => $value,
						]
					)
				)
			);
		}
		
		public static function different(string $field, string $value): Criteria
		{
			return CriteriaMother::create(
				FiltersMother::createOne(
					FilterMother::fromValues(
						[
							'field' => $field,
							'operator' => '<>',
							'value' => $value,
						]
					)
				)
			);
		}
	}
