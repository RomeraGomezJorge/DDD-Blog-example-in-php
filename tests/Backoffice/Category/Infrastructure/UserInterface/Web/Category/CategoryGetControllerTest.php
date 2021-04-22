<?php
	
	namespace App\Tests\Backoffice\Category\Infrastructure\UserInterface\Web\Category;
	
	
	use App\Backoffice\Category\Domain\Category;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Backoffice\Category\CategoryInfrastructureTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	
	final class CategoryGetControllerTest extends CategoryInfrastructureTestCase
	{
		private Category $Category;
		private Uuid $id;
		private $client;
		
		/** @test */
		public function it_should_find_categories()
		{
			$anotherCategory = CategoryMother::random();
			$someOtherCategory = CategoryMother::random();
			$this->repository()->save($this->Category);
			$this->repository()->save($anotherCategory);
			$this->repository()->save($someOtherCategory);
			
			$this->client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
			
			$this->isOnPage($this->client, self::LIST_CATEGORY_ITEMS_PATH);
			
			$this->shouldFindOnThePage(
				$this->client,
				$this->Category->description()
			);
			
			$this->shouldFindOnThePage(
				$this->client,
				$anotherCategory->description()
			);
			
			$this->shouldFindOnThePage(
				$this->client,
				$someOtherCategory->description()
			);
		}
		
		/** @test */
		public function it_should_show_interface_to_create_a_category()
		{
			$crawler = $this->isOnPage($this->client, self::LIST_CATEGORY_ITEMS_PATH);
			
			$this->clickAndFollowTheLink($this->client, $crawler, 'a#createItemLink');
			
			$this->shouldFindOnThePage($this->client, self::LABEL_TO_CREATE_CATEGORY_ITEMS);
		}
		
		/** @test */
		public function it_should_find_categories_by_criteria()
		{
			$filterByField = 'description';
			
			$anotherCategory = CategoryMother::random();
			$this->repository()->save($this->Category);
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
		
		/** @test */
		public function it_should_show_interface_to_edit_a_category()
		{
			$this->repository()->save($this->Category);
			
			$crawler = $this->isOnPage($this->client, self::LIST_CATEGORY_ITEMS_PATH);
			
			$this->clickAndFollowTheLink($this->client, $crawler, 'a.editItemLink');
			
			$this->shouldFindOnThePage($this->client, self::LABEL_TO_UPDATE_CATEGORY_ITEMS);
		}
		
		protected function setUp(): void
		{
			parent::setUp();
			
			$this->Category = CategoryMother::random();
			
			$this->id = new Uuid($this->Category->id());
			
			$this->client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
		}
	}