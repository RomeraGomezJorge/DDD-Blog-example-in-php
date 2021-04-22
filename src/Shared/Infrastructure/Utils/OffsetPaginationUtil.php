<?php
	
	
	namespace App\Shared\Infrastructure\Utils;
	
	
	use InvalidArgumentException;
	
	final class OffsetPaginationUtil
	{
		public static function calculate(int $limit, int $page): int
		{
			self::ensureIsGreaterThanZero($page);
			
			self::ensureIsGreaterThanZero($limit);
			
			return ($limit * ($page - 1));
		}
		
		private static function ensureIsGreaterThanZero(int $value): void
		{
			if ($value < 1) {
				throw new InvalidArgumentException('Página no encontrada');
			}
		}
	}