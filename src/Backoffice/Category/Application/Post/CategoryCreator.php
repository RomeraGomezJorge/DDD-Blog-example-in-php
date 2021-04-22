<?php
	
	namespace App\Backoffice\Category\Application\Post;
	
	use App\Backoffice\Category\Application\Get\Single\CategoryFinder;
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\CategoryDescriptionIsAvailableSpecification;
	use App\Backoffice\Category\Domain\CategoryPositionIsAvailableSpecification;
	use App\Backoffice\Category\Domain\CategoryRepository;
	use App\Backoffice\Category\Domain\ValueObject\CategoryDescription;
	use App\Backoffice\Category\Domain\ValueObject\CategoryPosition;
	use App\Shared\Domain\Bus\Event\EventBus;
	use App\Shared\Domain\SlugGenerator;
	use App\Shared\Domain\ValueObject\Uuid;
	use DateTime;
	
	final class CategoryCreator
	{
		const PARENT_CATEGORY_IS_NOT_DEFINED = null;
		private CategoryRepository $repository;
		private CategoryDescriptionIsAvailableSpecification $categoryDescriptionIsAvailableSpecification;
		private EventBus $bus;
		private CategoryFinder $finder;
		private CategoryPositionIsAvailableSpecification $categoryPositionIsAvailableSpecification;
		private SlugGenerator $slugGenerator;
		
		public function __construct(
			CategoryRepository $repository,
			CategoryDescriptionIsAvailableSpecification $categoryDescriptionIsAvailableSpecification,
			CategoryPositionIsAvailableSpecification $categoryPositionIsAvailableSpecification,
			SlugGenerator $slugGenerator,
			EventBus $bus
		) {
			$this->repository = $repository;
			$this->categoryDescriptionIsAvailableSpecification = $categoryDescriptionIsAvailableSpecification;
			$this->categoryPositionIsAvailableSpecification = $categoryPositionIsAvailableSpecification;
			$this->bus = $bus;
			$this->finder = new CategoryFinder($repository);
			$this->slugGenerator = $slugGenerator;
		}
		
		public function __invoke(string $id, string $description, int $position, ?string $parentId = null)
		{
			$parent = $this->getParentOrNull($parentId);
			
			$createAt = new DateTime();
			
			$category = Category::create(
				new Uuid($id),
				new CategoryDescription($description),
				new CategoryPosition($position),
				$parent,
				$createAt,
				$this->categoryDescriptionIsAvailableSpecification,
				$this->categoryPositionIsAvailableSpecification,
				$this->slugGenerator);
			
			$this->repository->save($category);
			
			$this->bus->publish(...$category->pullDomainEvents());
		}
		
		private function getParentOrNull(?string $parentId): ?Category
		{
			return $parentId === self::PARENT_CATEGORY_IS_NOT_DEFINED
				? $parentId
				: $this->finder->__invoke($parentId);
		}
	}