<?php
	
	
	namespace App\Shared\Infrastructure\Utils;
	
	
	final class PreviousPage
	{
		public static function calculate(int $page)
		{
			$previousPage = $page - 1;
			
			if (self::isLessThanOne($previousPage)) {
				return 1;
			}
			
			return $previousPage;
		}
		
		private static function isLessThanOne($previousPage): bool
		{
			return $previousPage < 1;
		}
	}