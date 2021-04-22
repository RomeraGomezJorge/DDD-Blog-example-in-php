<?php
	
	
	namespace App\Shared\Infrastructure\Utils;
	
	
	final class NextPage
	{
		public static function calculate(int $page, int $totalNumberOfPages)
		{
			if (self::isOnlyOnePage($totalNumberOfPages)) {
				return 1;
			}
			
			$nextPage = $page + 1;
			
			if (self::isNextPageGreaterThanTotalNumberOfPages($totalNumberOfPages, $nextPage)) {
				return $totalNumberOfPages;
			}
			
			return $nextPage;
		}
		
		private static function isOnlyOnePage($totalPage): bool
		{
			return $totalPage == 1;
		}
		
		private static function isNextPageGreaterThanTotalNumberOfPages(int $totalNumberOfPages, int $nextPage): bool
		{
			return $nextPage > $totalNumberOfPages;
		}
	}