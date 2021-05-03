<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Domain;
	
	class CategoryNameIsAvailableSpecification
	{
		const PARENT_CATEGORY_IS_NOT_DEFINED = null;
		private CategoryRepository $repository;
		
		public function __construct(CategoryRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function isSatisfiedBy(Category $category): bool
		{
			$categoryCriteria['name'] = $category->name();
			
			$categoryCriteria['parent'] = ($category->parent() === self::PARENT_CATEGORY_IS_NOT_DEFINED)
				? $category->parent()
				: $category->parent()->id();
			
			return $this->repository->isAvailable($categoryCriteria);
		}
	}