<?php
	
	
	namespace App\Tests\Backoffice\User\Infrastructure\UserInterface\Web;
	
	
	use App\Tests\Backoffice\User\UserInfrastructureTestCase;
	
	
	final class UserAddControllerTest extends UserInfrastructureTestCase
	{
		/** @test */
		public function it_should_create_a_user()
		{
			$user = $this->getUserCreatedForTestAndClearUnitOfWork();
			
			$client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
			
			$crawler = $this->isOnPage($client, self::CREATE_ITEM_PATH);
			
			$form = $crawler->selectButton('submitBtn')->form();
			
			$form['username'] = $user->getUsername();
			$form['name'] = $user->getName();
			$form['surname'] = $user->getSurname();
			$form['email'] = $user->getEmail();
			$form['password'] = $user->getPassword();
			$form['role_id'] = $user->getRole()->getId();
			$form['is_active'] = 'on';
			
			$client->submit($form);
			
			$this->shouldPageRedirectsTo($client, self::LIST_ITEMS_PATH);
		}
	}