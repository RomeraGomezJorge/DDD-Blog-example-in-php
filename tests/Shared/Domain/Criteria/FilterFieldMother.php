<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Domain\Criteria;
	
	use App\Shared\Domain\Criteria\FilterField;
	use App\Tests\Shared\Domain\WordMother;
	
	final class FilterFieldMother
	{
		public static function random(): FilterField
		{
			return self::create(WordMother::random());
		}
		
		public static function create($fieldName): FilterField
		{
			return new FilterField($fieldName);
		}
	}
