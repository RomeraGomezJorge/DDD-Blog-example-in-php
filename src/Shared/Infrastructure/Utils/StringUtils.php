<?php
	
	namespace App\Shared\Infrastructure\Utils;
	
	final class StringUtils
	{
		const VALUES_ARE_EQUAL = 0;
		
		public static function equals(string $firstString, string $secondString): bool
		{
			return strcmp($firstString, $secondString) === self::VALUES_ARE_EQUAL;
		}
	}