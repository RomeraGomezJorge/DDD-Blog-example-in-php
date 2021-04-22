<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Author\Infrastructure\UserInterface\Web;
	
	use App\Tests\Backoffice\Author\AuthorInfrastructureTestCase;
	use App\Tests\Backoffice\Author\Domain\AuthorMother;
	
	final class AuthorEditControllerTest extends AuthorInfrastructureTestCase
	{
		/** @test */
		public function it_should_update_an_author()
		{
			$author = AuthorMother::random();
			
			$this->repository()->save($author);
			
			$client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
			
			$crawler = $this->isOnPage($client, self::EDIT_ITEM_PATH . '/' . $author->id());
			
			$form = $crawler->selectButton('submitBtn')->form();
			
			$form['fullname'] = $author->fullname();
			
			$form['id'] = $author->id();
			
			$client->submit($form);
			
			$this->shouldPageRedirectsTo($client, self::LIST_ITEMS_PATH);
		}
	}