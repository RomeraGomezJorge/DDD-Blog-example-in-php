<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Domain;
	
	use App\Shared\Domain\Criteria\Criteria;
	use App\Shared\Domain\ValueObject\Uuid;
	
	interface CategoryRepository
	{
		public function save(Category $Category): void;
		
		public function search(Uuid $id): ?Category;
		
		public function searchAll(): array;
		
		public function matching(Criteria $criteria, ?Category $parent): array;
		
		public function totalMatchingRows(Criteria $criteria, ?Category $parent): int;
		
		public function delete(Category $Category): void;
		
		public function isAvailable(array $criteria);
		
		public function getAllParentCategoriesSortedByPosition():?array;
	}