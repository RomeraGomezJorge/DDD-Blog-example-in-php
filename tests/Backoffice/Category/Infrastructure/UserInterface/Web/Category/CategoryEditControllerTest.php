<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Category\Infrastructure\UserInterface\Web\Category;
	
	use App\Tests\Backoffice\Category\CategoryInfrastructureTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	
	final class CategoryEditControllerTest extends CategoryInfrastructureTestCase
	{
		/** @test */
		public function it_should_update_a_traffic_police_booth()
		{
			$Category = CategoryMother::random();
			
			$this->repository()->save($Category);
			
			$client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
			
			$crawler = $this->isOnPage($client, self::EDIT_CATEGORY_ITEM_PATH . '/' . $Category->id());
			
			$form = $crawler->selectButton('submitBtn')->form();
			
			$form['description'] = $Category->description();
			
			$form['position'] = $Category->position();
			
			$form['id'] = $Category->id();
			
			$client->submit($form);
			
			$this->shouldPageRedirectsTo($client, self::LIST_CATEGORY_ITEMS_PATH);
		}
	}