<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Domain\Criteria;
	
	use App\Shared\Domain\Criteria\Filter;
	use App\Shared\Domain\Criteria\FilterField;
	use App\Shared\Domain\Criteria\FilterOperator;
	use App\Shared\Domain\Criteria\FilterValue;
	
	final class FilterMother
	{
		public static function fromValues($values): Filter
		{
			return Filter::fromValues($values);
		}
		
		public static function random(): Filter
		{
			return self::create(FilterFieldMother::random(), FilterOperator::random(), FilterValueMother::random());
		}
		
		public static function create(FilterField $field, FilterOperator $operator, FilterValue $value): Filter
		{
			return new Filter($field, $operator, $value);
		}
	}
