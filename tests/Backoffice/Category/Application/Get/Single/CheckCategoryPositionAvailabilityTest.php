<?php
	
	
	namespace App\Tests\Backoffice\Category\Application\Get\Single;
	
	
	use App\Backoffice\Category\Application\Get\Single\CheckCategoryPositionAvailability;
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\Exception\CategoryNotExist;
	use App\Tests\Backoffice\Category\CategoryModuleUnitTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	use App\Tests\Shared\Domain\UuidMother;
	use InvalidArgumentException;
	
	final class CheckCategoryPositionAvailabilityTest extends CategoryModuleUnitTestCase
	{
		private CheckCategoryPositionAvailability $positionChecker;
		private Category $category;
		private Category $subcategory;
		
		/** @test */
		public function it_should_return_true_is_category_position_is_available()
		{
			$criteria = [
				'position' => $this->category->position(),
				'parent' => self::PARENT_CATEGORY_IS_NOT_DEFINED
			];
			
			$this->shouldBeAvailable($criteria);
			
			$isAvailable = $this->positionChecker->__invoke($this->category->position(),
				self::PARENT_CATEGORY_IS_NOT_DEFINED);
			
			$this->assertEquals($isAvailable, self::POSITION_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_true_is_subcategory_position_is_available()
		{
			$criteria = [
				'position' => $this->subcategory->position(),
				'parent' => $this->category
			];
			
			$this->shouldFind($this->category);
			
			$this->shouldBeAvailable($criteria);
			
			$isAvailable = $this->positionChecker->__invoke(
				$this->subcategory->position(),
				$this->category->id());
			
			$this->assertEquals($isAvailable, self::POSITION_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_category_position_is_unavailable()
		{
			$criteria = [
				'position' => $this->category->position(),
				'parent' => self::PARENT_CATEGORY_IS_NOT_DEFINED
			];
			
			$this->shouldBeUnavailable($criteria);
			
			$isAvailable = $this->positionChecker->__invoke($this->category->position(),
				self::PARENT_CATEGORY_IS_NOT_DEFINED);
			
			$this->assertEquals($isAvailable, self::POSITION_IS_NOT_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_subcategory_position_is_unavailable()
		{
			$criteria = [
				'position' => $this->subcategory->position(),
				'parent' => $this->category
			];
			
			$this->shouldFind($this->category);
			
			$this->shouldBeUnavailable($criteria);
			
			$isAvailable = $this->positionChecker->__invoke(
				$this->subcategory->position(),
				$this->category->id()
			);
			
			$this->assertEquals($isAvailable, self::POSITION_IS_NOT_AVAILABLE);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_parent_id_is_not_valid(): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldNotCheckAvailability();
			
			$this->positionChecker->__invoke(
				$this->category->position(),
				UuidMother::invalid()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_parent_category_does_not_exit(): void
		{
			$this->expectException(CategoryNotExist::class);
			
			$this->shouldNotFind($this->category);
			
			$this->shouldNotCheckAvailability();
			
			$this->positionChecker->__invoke(
				$this->subcategory->position(),
				$this->category->id()
			);
		}
		
		protected function setUp(): void
		{
			$this->positionChecker = new CheckCategoryPositionAvailability($this->repository());
			
			parent::setUp();
			
			$this->category = CategoryMother::random();
			$this->subcategory = CategoryMother::randomWithParentCategory($this->category);
		}
	}