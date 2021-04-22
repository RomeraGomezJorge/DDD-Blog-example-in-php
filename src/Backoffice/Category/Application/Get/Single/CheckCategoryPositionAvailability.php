<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Application\Get\Single;
	
	use App\Backoffice\Category\Domain\CategoryPositionIsAvailableSpecification;
	use App\Backoffice\Category\Domain\CategoryRepository;
	
	final class CheckCategoryPositionAvailability
	{
		const PARENT_CATEGORY_IS_NOT_DEFINED = null;
		private CategoryPositionIsAvailableSpecification $categoryPositionIsAvailableSpecification;
		private CategoryRepository $repository;
		private CategoryFinder $finder;
		
		public function __construct(CategoryRepository $repository)
		{
			$this->repository = $repository;
			
			$this->finder = new CategoryFinder($repository);
		}
		
		public function __invoke(string $position, ?string $parentId = null): bool
		{
			$categoryCriteria['position'] = trim($position);
			
			$categoryCriteria['parent'] = ($parentId === self::PARENT_CATEGORY_IS_NOT_DEFINED)
				? $parentId
				: $this->finder->__invoke(trim($parentId));
			
			return $this->repository->isAvailable($categoryCriteria);
		}
	}