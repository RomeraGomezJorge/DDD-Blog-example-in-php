<?php
	
	
	namespace App\Shared\Infrastructure\Utils;
	
	
	use InvalidArgumentException;
	
	final class TotalNumberOfPagesUtil
	{
		private int $page;
		private int $limit;
		private int $numberOfItems;
		
		public static function calculate(int $page, int $limit, int $numberOfItems): int
		{
			self::ensureIsGreaterThanZero($page, 'page');
			
			self::ensureIsGreaterThanZero($limit, 'limit');
			
			$total = ceil($numberOfItems / $limit);
			
			/* it happen when apply filter and not result found */
			if ($total < 1) {
				return 1;
			}
			
			self::ensurePageIsNoTGreaterThanTotalPage($page, $total);
			
			return $total;
		}
		
		private static function ensureIsGreaterThanZero(int $value, string $paramName): void
		{
			if ($value < 1) {
				throw new InvalidArgumentException('El valor ' . $value . ' no es valido para el parametro ' . $paramName);
			}
		}
		
		private static function ensurePageIsNoTGreaterThanTotalPage(int $page, int $totalPage): void
		{
			if ($page > $totalPage) {
				throw new InvalidArgumentException('La página seleccionada no existe.');
			}
		}
	}