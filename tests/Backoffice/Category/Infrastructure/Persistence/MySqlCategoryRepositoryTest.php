<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Category\Infrastructure\Persistence;
	
	use App\Backoffice\Category\Domain\Category;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Backoffice\Category\CategoryInfrastructureTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	use App\Tests\Shared\Domain\Criteria\CriteriaMother;
	use App\Tests\Shared\Domain\IntegerMother;
	use App\Tests\Shared\Domain\WordMother;
	
	
	final class MySqlCategoryRepositoryTest extends CategoryInfrastructureTestCase
	{
		private Category $category;
		private Uuid $id;
		private $description;
		private $unavailablePosition;
		
		/** @test */
		public function it_should_save_a_category(): void
		{
			$this->repository()->save($this->category);
			
			$this->clearUnitOfWork();
			
			$this->assertNotNull($this->repository()->search($this->id));
		}
		
		/** @test */
		public function it_should_save_a_subcategory(): void
		{
			$parent = $this->getRandomCategoryFromDatabase();
			
			$subcategory = CategoryMother::randomWithParentCategory($parent);
			
			$this->repository()->save($subcategory);
			
			$this->clearUnitOfWork();
			
			$this->assertNotNull(
				$this->repository()->search(
					new Uuid($subcategory->id())
				)
			);
		}
		
		/** @test */
		public function it_should_return_an_existing_category(): void
		{
			$this->repository()->save($this->category);
			
			$this->clearUnitOfWork();
			
			$categoryFound = $this->repository()->search($this->id);
			
			$this->assertEquals($this->category->id(), $categoryFound->id());
			$this->assertEquals($this->category->description(), $categoryFound->description());
			$this->assertEquals($this->category->position(), $categoryFound->position());
		}
		
		/** @test */
		public function it_should_return_an_existing_subcategory(): void
		{
			$categoryFound = $this->getRandomCategoryFromDatabase();
			
			$subcategory = CategoryMother::randomWithParentCategory($categoryFound);
			
			$this->repository()->save($subcategory);
			
			$this->clearUnitOfWork();
			
			$subcategoryFound = $this->repository()->search(
				new Uuid($subcategory->id())
			);
			
			$this->assertEquals($subcategory->id(), $subcategoryFound->id());
			$this->assertEquals($subcategory->description(), $subcategoryFound->description());
			$this->assertEquals($subcategory->position(), $subcategoryFound->position());
			$this->assertEquals($subcategory->parent()->id(), $subcategoryFound->parent()->id());
		}
		
		/** @test */
		public function it_should_not_return_a_non_existing_category(): void
		{
			$this->assertNull(
				$this->repository()->search($this->id)
			);
		}
		
		/** @test */
		public function it_should_delete_an_existing_category()
		{
			$this->repository()->save($this->category);
			
			$this->clearUnitOfWork();
			
			$itemFound = $this->repository()->search($this->id);
			
			$this->assertNull(
				$this->repository()->delete($itemFound)
			);
		}
		
		/** @test */
		public function it_should_search_all_existing_category(): void
		{
			$existingAuthor = CategoryMother::random();
			$anotherExistingAuthor = CategoryMother::random();
			$existingAuthors = [$existingAuthor, $anotherExistingAuthor];
			
			$this->repository()->save($existingAuthor);
			$this->repository()->save($anotherExistingAuthor);
			$this->clearUnitOfWork();
			
			$this->assertEquals(
				count($existingAuthors),
				count($this->repository()->searchAll())
			);
		}
		
		/** @test */
		public function it_should_search_all_existing_subcategory(): void
		{
			$categoryFound = $this->getRandomCategoryFromDatabase();
			
			$existingAuthor = CategoryMother::randomWithParentCategory($categoryFound);
			$anotherExistingAuthor = CategoryMother::randomWithParentCategory($categoryFound);
			$existingAuthors = [$existingAuthor, $anotherExistingAuthor];
			
			$this->repository()->save($existingAuthor);
			$this->repository()->save($anotherExistingAuthor);
			$this->clearUnitOfWork();
			
			$this->assertEquals(
				count($existingAuthors),
				count($this->repository()->matching(CriteriaMother::different('parent', 'NULL'),
					self::PARENT_CATEGORY_IS_NOT_DEFINED_AS_FILTER))
			);
		}
		
		/** @test */
		public function it_should_search_all_existing_categories_with_an_empty_criteria(): void
		{
			$existingItem = CategoryMother::random();
			$anotherExistingItem = CategoryMother::random();
			$existingItems = [$existingItem, $anotherExistingItem];
			
			$this->repository()->save($existingItem);
			$this->repository()->save($anotherExistingItem);
			$this->clearUnitOfWork();
			
			$this->assertEquals(
				count($existingItems),
				count($this->repository()->matching(CriteriaMother::empty(),
					self::PARENT_CATEGORY_IS_NOT_DEFINED_AS_FILTER))
			);
		}
		
		/** @test */
		public function it_should_filter_by_criteria(): void
		{
			$dddInPhpItem = CategoryMother::randomWithDescription('DDD en PHP');
			$dddInJavaItem = CategoryMother::randomWithDescription('DDD en Java');
			$intellijItem = CategoryMother::randomWithDescription('Exprimiendo Intellij');
			$dddAuthors = [$dddInPhpItem, $dddInJavaItem];
			
			$fullNameContainsDddCriteria = CriteriaMother::contains('description', 'DDD');
			
			$this->repository()->save($dddInJavaItem);
			$this->repository()->save($dddInPhpItem);
			$this->repository()->save($intellijItem);
			$this->clearUnitOfWork();
			
			$this->assertEquals(
				count($dddAuthors),
				count($this->repository()->matching($fullNameContainsDddCriteria,
					self::PARENT_CATEGORY_IS_NOT_DEFINED_AS_FILTER))
			);
		}
		
		/** @test */
		public function it_should_filter_by_parent_id_criteria(): void
		{
			$categoryFound = $this->getRandomCategoryFromDatabase();
			
			$existingAuthor = CategoryMother::randomWithParentCategory($categoryFound);
			$anotherExistingAuthor = CategoryMother::randomWithParentCategory($categoryFound);
			$existingAuthors = [$existingAuthor, $anotherExistingAuthor];
			
			$this->repository()->save($existingAuthor);
			$this->repository()->save($anotherExistingAuthor);
			$this->clearUnitOfWork();
			
			$this->assertEquals(
				count($existingAuthors),
				count($this->repository()->matching(CriteriaMother::empty(), $categoryFound))
			);
		}
		
		/** @test */
		public function it_should_return_true_is_category_description_is_available()
		{
			$category = CategoryMother::random();
			
			$this->repository()->save($category);
			
			$availableDescription = WordMother::random();
			
			$categoryCriteria = [
				'description' => $availableDescription,
				'parent' => self::PARENT_CATEGORY_IS_NOT_DEFINED_AS_FILTER
			];
			
			$isAvailable = $this->repository()->isAvailable($categoryCriteria);
			
			$this->assertEquals($isAvailable, self::DESCRIPTION_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_true_is_subcategory_description_is_available()
		{
			$subcategory = $this->getRandomSubcategoryFromDatabase();
			
			$subcategoryCriteria = [
				'description' => WordMother::random(),
				'parent' => $subcategory->parent()->id()
			];
			
			$isAvailable = $this->repository()->isAvailable($subcategoryCriteria);
			
			$this->assertEquals($isAvailable, self::DESCRIPTION_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_category_description_is_unavailable()
		{
			$category = CategoryMother::random();
			
			$this->repository()->save($category);
			
			$categoryCriteria = [
				'description' => $category->description(),
				'parent' => self::PARENT_CATEGORY_IS_NOT_DEFINED_AS_FILTER
			];
			
			$isAvailable = $this->repository()->isAvailable($categoryCriteria);
			
			$this->assertEquals($isAvailable, self::DESCRIPTION_IS_NOT_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_subcategory_description_is_unavailable()
		{
			$subcategory = $this->getRandomSubcategoryFromDatabase();
			
			$categoryCriteria = [
				'description' => $subcategory->description(),
				'parent' => $subcategory->parent()->id()
			];
			
			$isAvailable = $this->repository()->isAvailable($categoryCriteria);
			
			$this->assertEquals($isAvailable, self::DESCRIPTION_IS_NOT_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_true_is_category_position_is_available()
		{
			$category = CategoryMother::random();
			
			$this->repository()->save($category);
			
			$availablePosition = IntegerMother::random();
			
			$categoryCriteria = [
				'position' => $availablePosition,
				'parent' => self::PARENT_CATEGORY_IS_NOT_DEFINED_AS_FILTER
			];
			
			$isAvailable = $this->repository()->isAvailable($categoryCriteria);
			
			$this->assertEquals($isAvailable, self::POSITION_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_true_is_subcategory_position_is_available()
		{
			$subcategory = $this->getRandomSubcategoryFromDatabase();
			
			$availablePosition = WordMother::random();
			
			$subcategoryCriteria = [
				'position' => $availablePosition,
				'parent' => $subcategory->parent()->id()
			];
			
			$isAvailable = $this->repository()->isAvailable($subcategoryCriteria);
			
			$this->assertEquals($isAvailable, self::POSITION_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_category_position_is_unavailable()
		{
			$category = CategoryMother::random();
			
			$this->repository()->save($category);
			
			$unavailableDescription = $category->description();
			
			$categoryCriteria = [
				'description' => $unavailableDescription,
				'parent' => self::PARENT_CATEGORY_IS_NOT_DEFINED_AS_FILTER
			];
			
			$isAvailable = $this->repository()->isAvailable($categoryCriteria);
			
			$this->assertEquals($isAvailable, self::DESCRIPTION_IS_NOT_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_subcategory_position_is_unavailable()
		{
			$subcategory = $this->getRandomSubcategoryFromDatabase();
			
			$unavailablePosition = $subcategory->position();
			
			$categoryCriteria = [
				'position' => $unavailablePosition,
				'parent' => $subcategory->parent()->id()
			];
			
			$isAvailable = $this->repository()->isAvailable($categoryCriteria);
			
			$this->assertEquals($isAvailable, self::DESCRIPTION_IS_NOT_AVAILABLE);
		}
		
		protected function setUp(): void
		{
			parent::setUp(); // TODO: Change the autogenerated stub
			
			$this->category = CategoryMother::random();
			
			$this->id = new Uuid($this->category->id());
		}
	}
