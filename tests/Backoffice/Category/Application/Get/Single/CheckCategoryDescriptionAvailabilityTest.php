<?php
	
	
	namespace App\Tests\Backoffice\Category\Application\Get\Single;
	
	
	use App\Backoffice\Category\Application\Get\Single\CheckCategoryNameAvailability;
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\Exception\CategoryNotExist;
	use App\Tests\Backoffice\Category\CategoryModuleUnitTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	use App\Tests\Shared\Domain\UuidMother;
	use InvalidArgumentException;
	
	final class CheckCategoryDescriptionAvailabilityTest extends CategoryModuleUnitTestCase
	{
		private CheckCategoryNameAvailability $descriptionChecker;
		private Category $category;
		private Category $subcategory;
		
		/** @test */
		public function it_should_return_true_is_category_description_is_available()
		{
			$criteria = [
				'description' => $this->category->name(),
				'parent' => self::PARENT_CATEGORY_IS_NOT_DEFINED
			];
			
			$this->shouldBeAvailable($criteria);
			
			$isAvailable = $this->descriptionChecker->__invoke($this->category->name(),
				self::PARENT_CATEGORY_IS_NOT_DEFINED);
			
			$this->assertEquals($isAvailable, self::DESCRIPTION_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_true_is_subcategory_description_is_available()
		{
			$criteria = [
				'description' => $this->subcategory->name(),
				'parent' => $this->category
			];
			
			$this->shouldFind($this->category);
			
			$this->shouldBeAvailable($criteria);
			
			$isAvailable = $this->descriptionChecker->__invoke(
				$this->subcategory->name(),
				$this->category->id());
			
			$this->assertEquals($isAvailable, self::DESCRIPTION_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_category_description_is_unavailable()
		{
			$criteria = [
				'description' => $this->category->name(),
				'parent' => self::PARENT_CATEGORY_IS_NOT_DEFINED
			];
			
			$this->shouldBeUnavailable($criteria);
			
			$isAvailable = $this->descriptionChecker->__invoke($this->category->name(),
				self::PARENT_CATEGORY_IS_NOT_DEFINED);
			
			$this->assertEquals($isAvailable, self::DESCRIPTION_IS_NOT_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_subcategory_description_is_unavailable()
		{
			$criteria = [
				'description' => $this->subcategory->name(),
				'parent' => $this->category
			];
			
			$this->shouldFind($this->category);
			
			$this->shouldBeUnavailable($criteria);
			
			$isAvailable = $this->descriptionChecker->__invoke(
				$this->subcategory->name(),
				$this->category->id()
			);
			
			$this->assertEquals($isAvailable, self::DESCRIPTION_IS_NOT_AVAILABLE);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_parent_id_is_not_valid(): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldNotCheckAvailability();
			
			$this->descriptionChecker->__invoke(
				$this->category->name(),
				UuidMother::invalid()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_parent_category_does_not_exit(): void
		{
			$this->expectException(CategoryNotExist::class);
			
			$this->shouldNotFind($this->category);
			
			$this->shouldNotCheckAvailability();
			
			$this->descriptionChecker->__invoke(
				$this->subcategory->name(),
				$this->category->id()
			);
		}
		
		protected function setUp(): void
		{
			$this->descriptionChecker = new CheckCategoryNameAvailability($this->repository());
			
			parent::setUp();
			
			$this->category = CategoryMother::random();
			$this->subcategory = CategoryMother::randomWithParentCategory($this->category);
		}
	}