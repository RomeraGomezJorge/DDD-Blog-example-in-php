<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Category\Application\Put;
	
	
	use App\Backoffice\Category\Application\Put\CategoryDetailsChanger;
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\Exception\CategoryNotExist;
	use App\Backoffice\Category\Domain\Exception\UnavailableCategoryName;
	use App\Backoffice\Category\Domain\Exception\UnavailableCategoryPosition;
	use App\Tests\Backoffice\Category\CategoryModuleUnitTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	use App\Tests\Shared\Domain\IntegerMother;
	use App\Tests\Shared\Domain\UuidMother;
	use App\Tests\Shared\Domain\WordMother;
	use InvalidArgumentException;
	
	
	final class CategoryDetailsChangerTest extends CategoryModuleUnitTestCase
	{
		private CategoryDetailsChanger $updater;
		private Category $category;
		private Category $subcategory;
		
		/** @test */
		public function it_should_update_an_existing_category_when_description_is_change(): void
		{
			$newDescription = WordMother::random();
			
			$this->shouldHaveUniqueCategoryDescription($this->category);
			
			$this->shouldGenerateSlug($newDescription);
			
			$this->shouldNotValidateIfCategoryPositionIsAvailable();
			
			$this->shouldFind($this->category);
			
			$this->shouldSave($this->category);
			
			$this->updater->__invoke(
				$this->category->id(),
				$newDescription,
				$this->category->position());
		}
		
		/** @test */
		public function it_should_update_an_existing_subcategory_when_description_is_change(): void
		{
			$newDescription = WordMother::random();
			
			$this->shouldHaveUniqueCategoryDescription($this->subcategory);
			
			$this->shouldGenerateSlug($newDescription);
		
			$this->shouldNotValidateIfCategoryPositionIsAvailable();
			
			$this->shouldFind($this->subcategory);
			
			$this->shouldFind($this->category);
			
			$this->shouldSave($this->subcategory);
			
			$this->updater->__invoke($this->subcategory->id(),$newDescription,$this->subcategory->position(),$this->category->id());
		}
		
		/** @test */
		public function it_should_not_update_an_existing_category_description_if_it_does_not_change(): void
		{
			$this->shouldNotValidateIfCategoryDescriptionIsAvailable();
			
			$this->shouldNotValidateIfCategoryPositionIsAvailable();
			
			$this->shouldGenerateSlug($this->category->name());
			
			$this->shouldFind($this->category);
			
			$this->shouldSave($this->category);
			
			$this->updater->__invoke($this->category->id(), $this->category->name(),$this->category->position());
		}
		
		/** @test */
		public function it_should_not_update_an_existing_subcategory_description_if_it_does_not_change(): void
		{
			$this->shouldNotValidateIfCategoryDescriptionIsAvailable();
			
			$this->shouldNotValidateIfCategoryPositionIsAvailable();
			
			$this->shouldGenerateSlug($this->subcategory->name());
			
			$this->shouldFind($this->subcategory);
			
			$this->shouldFind($this->category);
			
			$this->shouldSave($this->subcategory);
			
			$this->updater->__invoke(
				$this->subcategory->id(),
				$this->subcategory->name(),
				$this->subcategory->position(),
				$this->category->id());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_category_does_not_exit(): void
		{
			$this->expectException(CategoryNotExist::class);
			
			$this->shouldNotFind($this->category);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke(
				$this->category->id(),
				$this->category->name(),
				$this->category->position());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_parent_category_does_not_exit(): void
		{
			$this->expectException(CategoryNotExist::class);
			
			$this->shouldFind($this->subcategory);
			
			$this->shouldNotFind($this->category);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke(
				$this->subcategory->id(),
				$this->subcategory->name(),
				$this->subcategory->position(),
				$this->category->id());
		}
		
		/** @test */
		public function it_should_update_an_existing_category_when_position_is_change(): void
		{
			$newPosition = IntegerMother::random();
			
			$this->shouldNotValidateIfCategoryDescriptionIsAvailable();
			
			$this->shouldHaveUniqueCategoryPosition($this->category);
			
			$this->shouldGenerateSlug($this->category->name());
			
			$this->shouldFind($this->category);
			
			$this->shouldSave($this->category);
			
			$this->updater->__invoke(
				$this->category->id(),
				$this->category->name(),
				$newPosition);
		}
		
		/** @test */
		public function it_should_update_an_existing_subcategory_when_position_is_change(): void
		{
			$newPosition = IntegerMother::random();
			
			$this->shouldNotValidateIfCategoryDescriptionIsAvailable();
			
			$this->shouldHaveUniqueCategoryPosition($this->subcategory);
			
			$this->shouldGenerateSlug($this->subcategory->name());
			
			$this->shouldFind($this->subcategory);
			
			$this->shouldFind($this->category);
			
			$this->shouldSave($this->subcategory);
			
			$this->updater->__invoke(
				$this->subcategory->id(),
				$this->subcategory->name(),
				$newPosition,
				$this->category->id());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_category_description_is_not_available(): void
		{
			$unavailableDescription = WordMother::random();
			
			$this->expectException(UnavailableCategoryName::class);
			
			$this->shouldNotHaveUniqueCategoryDescription($this->category);
			
			$this->shouldFind($this->category);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke(
				$this->category->id(),
				$unavailableDescription,
				$this->category->position());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_subcategory_description_is_not_available(): void
		{
			$unavailableDescription = WordMother::random();
			
			$this->expectException(UnavailableCategoryName::class);
			
			$this->shouldNotHaveUniqueCategoryDescription($this->subcategory);
			
			$this->shouldFind($this->subcategory);
			
			$this->shouldFind($this->category);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke(
				$this->subcategory->id(),
				$unavailableDescription,
				$this->subcategory->position(),
				$this->category->id());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_category_position_is_not_available(): void
		{
			$unavailablePosition = IntegerMother::random();
			
			$this->expectException(UnavailableCategoryPosition::class);
			
			$this->shouldNotHaveUniqueCategoryPosition($this->category);
			
			$this->shouldFind($this->category);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke($this->category->id(), $this->category->name(), $unavailablePosition);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_subcategory_position_is_not_available(): void
		{
			$unavailablePosition = IntegerMother::random();
			
			$this->expectException(UnavailableCategoryPosition::class);
			
			$this->shouldNotHaveUniqueCategoryPosition($this->subcategory);
			
			$this->shouldFind($this->subcategory);
			
			$this->shouldFind($this->category);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke(
				$this->subcategory->id(),
				$this->subcategory->name(),
				$unavailablePosition,
				$this->category->id());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_id_is_not_valid(): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke(UuidMother::invalid(), $this->category->name(),
				$this->category->position());
		}
		
		protected function setUp(): void
		{
			parent::setUp(); // TODO: Change the autogenerated stub
			
			$this->updater = new CategoryDetailsChanger(
				$this->repository(),
				$this->categoryDescriptionIsAvailableSpecification(),
				$this->categoryPositionIsAvailableSpecification(),
				$this->slugGenerator()
			);
			
			$this->category = CategoryMother::random();
			
			$this->subcategory = CategoryMother::randomWithParentCategory($this->category);
		}
	}

