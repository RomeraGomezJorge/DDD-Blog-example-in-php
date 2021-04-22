<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Application\Put;
	
	use App\Backoffice\Category\Application\Get\Single\CategoryFinder;
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\CategoryDescriptionIsAvailableSpecification;
	use App\Backoffice\Category\Domain\CategoryPositionIsAvailableSpecification;
	use App\Backoffice\Category\Domain\CategoryRepository;
	use App\Backoffice\Category\Domain\ValueObject\CategoryDescription;
	use App\Backoffice\Category\Domain\ValueObject\CategoryPosition;
	use App\Shared\Domain\SlugGenerator;
	
	final class CategoryDetailsChanger
	{
		const PARENT_CATEGORY_IS_NOT_DEFINED = null;
		private CategoryRepository $repository;
		private CategoryFinder  $finder;
		private CategoryDescriptionIsAvailableSpecification $categoryDescriptionIsAvailableSpecification;
		private CategoryPositionIsAvailableSpecification $categoryPositionIsAvailableSpecification;
		private SlugGenerator $slugGenerator;
		
		public function __construct(
			CategoryRepository $repository,
			CategoryDescriptionIsAvailableSpecification $categoryDescriptionIsAvailableSpecification,
			CategoryPositionIsAvailableSpecification $categoryPositionIsAvailableSpecification,
			SlugGenerator $slugGenerator
		) {
			$this->repository = $repository;
			$this->finder = new CategoryFinder($repository);
			$this->categoryDescriptionIsAvailableSpecification = $categoryDescriptionIsAvailableSpecification;
			$this->categoryPositionIsAvailableSpecification = $categoryPositionIsAvailableSpecification;
			$this->slugGenerator = $slugGenerator;
		}
		
		public function __invoke(string $id, string $aNewDescription, int $aNewPosition, ?string $aNewParentId = null)
		{
			$categoryFound = $this->finder->__invoke($id);
			
			$parent = $this->getParentOrNull($aNewParentId);
			
			$categoryFound->changeDetails(
				new CategoryDescription($aNewDescription),
				new CategoryPosition($aNewPosition),
				$parent,
				$this->categoryDescriptionIsAvailableSpecification,
				$this->categoryPositionIsAvailableSpecification,
				$this->slugGenerator
			);
			
			$this->repository->save($categoryFound);
		}
		
		private function getParentOrNull(?string $newParentId): ?Category
		{
			return $newParentId === self::PARENT_CATEGORY_IS_NOT_DEFINED
				? $newParentId
				: $this->finder->__invoke($newParentId);
		}
	}