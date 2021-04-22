<?php
	
	
	namespace App\Shared\Infrastructure\Utils;
	
	
	final class SortUtils
	{
		public static function toggle($sort): string
		{
			return $sort === 'asc' ? 'desc' : 'asc';
		}
	}