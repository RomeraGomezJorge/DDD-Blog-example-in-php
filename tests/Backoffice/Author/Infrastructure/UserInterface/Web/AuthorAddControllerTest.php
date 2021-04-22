<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Author\Infrastructure\UserInterface\Web;
	
	use App\Tests\Backoffice\Author\AuthorInfrastructureTestCase;
	use App\Tests\Backoffice\Author\Domain\AuthorMother;
	
	final class AuthorAddControllerTest extends AuthorInfrastructureTestCase
	{
		/** @test */
		public function it_should_create_an_author()
		{
			$author = AuthorMother::random();
			
			$client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
			
			$crawler = $this->isOnPage($client, self::CREATE_ITEM_PATH);
			
			$form = $crawler->selectButton('submitBtn')->form();
			
			$form['fullname'] = $author->fullname();
			
			$client->submit($form);
			
			$this->shouldPageRedirectsTo($client, self::LIST_ITEMS_PATH);
		}
	}