<?php
	
	namespace App\Tests\Backoffice\Category\Infrastructure\UserInterface\Web\Subcategory;
	
	use App\Backoffice\Category\Domain\Category;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Backoffice\Category\CategoryInfrastructureTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	
	final class SubcategoryGetControllerTest extends CategoryInfrastructureTestCase
	{
		private Category $category;
		private Uuid $id;
		private $client;
		
		/** @test */
		public function it_should_find_subcategories()
		{
			$category = $this->getRandomCategoryFromDatabase();
			$anotherSubcategory = CategoryMother::randomWithParentCategory($category);
			$someOtherSubcategory = CategoryMother::randomWithParentCategory($category);
			$this->repository()->save($anotherSubcategory);
			$this->repository()->save($someOtherSubcategory);
			
			$this->client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
			
			$this->isOnPage($this->client, self::LIST_SUBCATEGORY_ITEMS_PATH);
			
			$this->shouldFindOnThePage($this->client, $anotherSubcategory->description());
			
			$this->shouldFindOnThePage($this->client, $someOtherSubcategory->description());
		}
		
		/** @test */
		public function it_should_show_interface_to_create_a_subcategory()
		{
			$crawler = $this->isOnPage($this->client, self::LIST_SUBCATEGORY_ITEMS_PATH);
			
			$this->clickAndFollowTheLink($this->client, $crawler, 'a#createItemLink');
			
			$this->shouldFindOnThePage($this->client, self::LABEL_TO_CREATE_SUBCATEGORY_ITEMS);
		}
		
		/** @test */
		public function it_should_find_subcategories_by_description_criteria()
		{
			$filterByField = 'description';
			
			$category = $this->getRandomCategoryFromDatabase();
			$subcategory = CategoryMother::randomWithParentCategory($category);
			$anotherCategory = CategoryMother::randomWithParentCategory($category);
			$this->repository()->save($subcategory);
			$this->repository()->save($anotherCategory);
			
			$this->isOnPage(
				$this->client,
				self::LIST_CATEGORY_ITEMS_PATH . '/page-1/order-createAt-desc/rows_per_page-10/filters%5B0%5D%5Bfield%5D=' . $filterByField . '&filters%5B0%5D%5Boperator%5D=%3D&filters%5B0%5D%5Bvalue%5D=' . $anotherCategory->description()
			);
			
			$this->shouldFindOnThePage(
				$this->client,
				$anotherCategory->description()
			);
		}
		
		public function it_should_find_subcategories_by_parent_criteria()
		{
			$filterByField = 'parent';
			
			$category = $this->getRandomCategoryFromDatabase();
			$subcategory = CategoryMother::randomWithParentCategory($category);
			$anotherSubcategory = CategoryMother::randomWithParentCategory($category);
			$this->repository()->save($subcategory);
			$this->repository()->save($anotherSubcategory);
			
			$this->isOnPage(
				$this->client,
				self::LIST_CATEGORY_ITEMS_PATH . '/page-1/order-createAt-desc/rows_per_page-10/filters%5B0%5D%5Bfield%5D=' . $filterByField . '&filters%5B0%5D%5Boperator%5D=%3D&filters%5B0%5D%5Bvalue%5D=' . $anotherSubcategory->parent()->id()
			);
			
			$this->shouldFindOnThePage(
				$this->client,
				$anotherSubcategory->description()
			);
		}
		
		/** @test */
		public function it_should_show_interface_to_edit_a_subcategory()
		{
			$category = $this->getRandomCategoryFromDatabase();
			
			$subcategory = CategoryMother::randomWithParentCategory($category);
			
			$this->repository()->save($subcategory);
			
			$crawler = $this->isOnPage($this->client, self::LIST_SUBCATEGORY_ITEMS_PATH);
			
			$this->clickAndFollowTheLink($this->client, $crawler, 'a.editItemLink');
			
			$this->shouldFindOnThePage($this->client, self::LABEL_TO_UPDATE_SUBCATEGORY_ITEMS);
		}
		
		protected function setUp(): void
		{
			parent::setUp();
			
			$this->category = CategoryMother::random();
			
			$this->id = new Uuid($this->category->id());
			
			$this->client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
		}
	}