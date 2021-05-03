<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Category\Infrastructure\UserInterface\Web\Category;
	
	use App\Tests\Backoffice\Category\CategoryInfrastructureTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	
	final class CategoryAddControllerTest extends CategoryInfrastructureTestCase
	{
		/** @test */
		public function it_should_create_a_category()
		{
			$category = CategoryMother::random();
			
			$client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
			
			$crawler = $this->isOnPage($client, self::CREATE_CATEGORY_ITEM_PATH);
			
			$form = $crawler->selectButton('submitBtn')->form();
			
			$form['description'] = $category->name();
			
			$form['position'] = $category->position();
			
			$client->submit($form);
			
			$this->shouldPageRedirectsTo($client, self::LIST_CATEGORY_ITEMS_PATH);
		}
	}