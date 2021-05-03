<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Category\Infrastructure\UserInterface\Web\Subcategory;
	
	use App\Tests\Backoffice\Category\CategoryInfrastructureTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	
	final class SubcategoryEditControllerTest extends CategoryInfrastructureTestCase
	{
		/** @test */
		public function it_should_update_a_subcategory()
		{
			$category = $this->getRandomCategoryFromDatabase();
			
			$subcategory = CategoryMother::randomWithParentCategory($category);
			
			$this->repository()->save($subcategory);
			
			$client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
			
			$crawler = $this->isOnPage($client, self::EDIT_SUBCATEGORY_ITEM_PATH . '/' . $subcategory->id());
			
			$form = $crawler->selectButton('submitBtn')->form();
			
			$form['description'] = $subcategory->name();
			
			$form['position'] = $subcategory->position();
			
			$form['parent_id'] = $category->id();
			
			$form['id'] = $subcategory->id();
			
			$client->submit($form);
			
			$this->shouldPageRedirectsTo($client, self::LIST_SUBCATEGORY_ITEMS_PATH);
		}
	}