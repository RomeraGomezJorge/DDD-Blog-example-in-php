<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Category;
	
	
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\CategoryDescriptionIsAvailableSpecification;
	use App\Backoffice\Category\Domain\CategoryPositionIsAvailableSpecification;
	use App\Backoffice\Category\Domain\CategoryRepository;
	use App\Shared\Domain\Bus\Event\EventBus;
	use App\Shared\Domain\SlugGenerator;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
	
	
	abstract class CategoryModuleUnitTestCase extends UnitTestCase
	{
		const DESCRIPTION_IS_AVAILABLE = true;
		const DESCRIPTION_IS_NOT_AVAILABLE = false;
		const POSITION_IS_AVAILABLE = true;
		const POSITION_IS_NOT_AVAILABLE = false;
		const PARENT_CATEGORY_IS_NOT_DEFINED = null;
		const CATEGORY_NOT_FOUND = null;
		const AVAILABLE = true;
		const UNAVAILABLE = false;
		protected $repository;
		protected $categoryDescriptionIsAvailableSpecification;
		protected $categoryPositionIsAvailableSpecification;
		protected $bus;
		private $slugGenerator;
		
		public function shouldHaveUniqueCategoryDescription(Category $category): void
		{
			$this->categoryDescriptionIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->once()
				->with($this->similarTo($category))
				->andReturn(self::DESCRIPTION_IS_AVAILABLE);
		}
		
		protected function categoryDescriptionIsAvailableSpecification(): CategoryDescriptionIsAvailableSpecification
		{
			return $this->categoryDescriptionIsAvailableSpecification = $this->categoryDescriptionIsAvailableSpecification ?: $this->mock(CategoryDescriptionIsAvailableSpecification::class);
		}
		
		protected function slugGenerator(): SlugGenerator
		{
			return $this->slugGenerator = $this->slugGenerator ?: $this->mock(SlugGenerator::class);
		}
		
		public function shouldNotHaveUniqueCategoryDescription(Category $category): void
		{
			$this->categoryDescriptionIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->once()
//				->with($this->similarTo($category))
				->andReturn(self::DESCRIPTION_IS_NOT_AVAILABLE);
		}
		
		public function shouldHaveUniqueCategoryPosition(Category $category)
		{
			$this->categoryPositionIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->once()
				->with($this->similarTo($category))
				->andReturn(self::POSITION_IS_AVAILABLE);
		}
		
		protected function categoryPositionIsAvailableSpecification(): CategoryPositionIsAvailableSpecification
		{
			return $this->categoryPositionIsAvailableSpecification = $this->categoryPositionIsAvailableSpecification ?: $this->mock(CategoryPositionIsAvailableSpecification::class);
		}
		
		public function shouldNotHaveUniqueCategoryPosition(Category $category)
		{
			$this->categoryPositionIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->once()
				->with($this->similarTo($category))
				->andReturn(self::POSITION_IS_NOT_AVAILABLE);
		}
		
		protected function shouldFind(Category $category): void
		{
			$id = new Uuid($category->id());
			$this->repository()
				->shouldReceive('search')
				->once()
				->with($this->similarTo($id))
				->andReturn($category);
		}
		
		protected function repository(): CategoryRepository
		{
			return $this->repository = $this->repository ?: $this->mock(CategoryRepository::class);
		}
		
		protected function shouldNotFind(Category $category): void
		{
			$id = new Uuid($category->id());
			
			$this->repository()
				->shouldReceive('search')
				->once()
				->with($this->similarTo($id))
				->andReturn(self::CATEGORY_NOT_FOUND);
		}
		
		protected function shouldSave(Category $Category): void
		{
			$this->repository()
				->shouldReceive('save')
				->once()
				->with($this->similarTo($Category))
				->andReturnNull();
		}
		
		protected function shouldNotSave()
		{
			$this->repository()
				->shouldReceive('save')
				->never();
		}
		
		protected function shouldNotPublish()
		{
			$this->bus()
				->shouldReceive('publish')
				->never();
		}
		
		protected function bus(): EventBus
		{
			return $this->bus = $this->bus ?: $this->mock(EventBus::class);
		}
		
		protected function shouldNotValidateIfCategoryDescriptionIsAvailable(): void
		{
			$this->categoryDescriptionIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->never();
		}
		
		protected function shouldNotValidateIfCategoryPositionIsAvailable(): void
		{
			$this->categoryPositionIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->never();
		}
		
		protected function shouldBeAvailable(array $criteria): void
		{
			$this->repository()
				->shouldReceive('isAvailable')
				->once()
				->with($criteria)
				->andReturn(self::AVAILABLE);
		}
		
		protected function shouldBeUnavailable(array $criteria): void
		{
			$this->repository()
				->shouldReceive('isAvailable')
				->once()
				->with($criteria)
				->andReturn(self::UNAVAILABLE);
		}
		
		protected function shouldNotCheckAvailability(): void
		{
			$this->repository()
				->shouldReceive('isAvailable')
				->never();
		}
		
		protected function shouldGenerateSlug(string $description):void
		{
			$this->slugGenerator()
				->shouldReceive('generate')
				->once()
				->with($description);
		}
	}
