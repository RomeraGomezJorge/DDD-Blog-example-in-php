<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Domain\Criteria;
	
	use App\Shared\Domain\Criteria\FilterValue;
	use App\Tests\Shared\Domain\WordMother;
	
	final class FilterValueMother
	{
		public static function random(): FilterValue
		{
			return self::create(WordMother::random());
		}
		
		public static function create($value): FilterValue
		{
			return new FilterValue($value);
		}
	}
