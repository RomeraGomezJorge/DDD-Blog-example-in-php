<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Category\Infrastructure\UserInterface\Web\Subcategory;
	
	use App\Tests\Backoffice\Category\CategoryInfrastructureTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	
	final class SubcategoryAddControllerTest extends CategoryInfrastructureTestCase
	{
		/** @test */
		public function it_should_create_a_subcategory()
		{
			$category = $this->getRandomCategoryFromDatabase();
			
			$subcategory = CategoryMother::randomWithParentCategory($category);
			
			$client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
			
			$crawler = $this->isOnPage($client, self::CREATE_SUBCATEGORY_ITEM_PATH);
			
			$form = $crawler->selectButton('submitBtn')->form();
			
			$form['description'] = $subcategory->description();
			
			$form['position'] = $subcategory->position();
			
			$form['parent_id'] = $category->id();
			
			$client->submit($form);
			
			$this->shouldPageRedirectsTo($client, self::LIST_SUBCATEGORY_ITEMS_PATH);
		}
	}