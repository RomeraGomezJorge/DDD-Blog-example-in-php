<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Application\Get\Single;
	
	use App\Backoffice\Category\Domain\CategoryRepository;
	
	final class CheckCategoryNameAvailability
	{
		const PARENT_CATEGORY_IS_NOT_DEFINED = null;
		private CategoryRepository $repository;
		private CategoryFinder $finder;
		
		public function __construct(CategoryRepository $repository)
		{
			$this->repository = $repository;
			$this->finder = new CategoryFinder($repository);
		}
		
		public function __invoke(string $name, ?string $parentId = null): bool
		{
			$categoryCriteria['name'] = trim($name);
			
			$categoryCriteria['parent'] = ($parentId === self::PARENT_CATEGORY_IS_NOT_DEFINED)
				? $parentId
				: $this->finder->__invoke(trim($parentId));
			
			return $this->repository->isAvailable($categoryCriteria);
		}
	}