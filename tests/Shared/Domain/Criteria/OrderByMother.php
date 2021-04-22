<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Domain\Criteria;
	
	use App\Shared\Domain\Criteria\OrderBy;
	use App\Tests\Shared\Domain\WordMother;
	
	final class OrderByMother
	{
		public static function random(): OrderBy
		{
			return self::create(WordMother::random());
		}
		
		public static function create($fieldName): OrderBy
		{
			return new OrderBy($fieldName);
		}
	}
