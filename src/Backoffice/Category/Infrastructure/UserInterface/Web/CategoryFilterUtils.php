<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web;
	
	final class CategoryFilterUtils
	{
		public static function filterToDisplayParent(array $filters = []): array
		{
			$filters[] = [
				'field' => 'parent',
				'operator' => '=',
				'value' => 'NULL'
			];
			
			return $filters;
		}
		
		public static function appendFilterToDisplayChildrenCategories(array $filters = []): array
		{
			$filters[] = [
				'field' => 'parent',
				'operator' => '<>',
				'value' => 'NULL'
			];
			
			return $filters;
		}
	}