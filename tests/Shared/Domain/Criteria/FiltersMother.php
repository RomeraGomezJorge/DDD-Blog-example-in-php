<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Domain\Criteria;
	
	use App\Shared\Domain\Criteria\Filter;
	use App\Shared\Domain\Criteria\Filters;
	
	final class FiltersMother
	{
		public static function createOne(Filter $filter): Filters
		{
			return self::create([$filter]);
		}
		
		/** @param Filter[] $filters */
		public static function create(array $filters): Filters
		{
			return new Filters($filters);
		}
		
		public static function blank(): Filters
		{
			return self::create([]);
		}
		
		public static function random(): Filters
		{
			return self::create([FilterMother::random()]);
		}
	}
