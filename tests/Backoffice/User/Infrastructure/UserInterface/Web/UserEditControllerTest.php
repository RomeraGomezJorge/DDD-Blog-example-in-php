<?php
	
	
	namespace App\Tests\Backoffice\User\Infrastructure\UserInterface\Web;
	
	
	use App\Tests\Backoffice\User\UserInfrastructureTestCase;
	
	final class UserEditControllerTest extends UserInfrastructureTestCase
	{
		/** @test */
		public function it_should_update_a_user()
		{
			$user = $this->getUserCreatedForTestAndClearUnitOfWork();
			
			$this->repository()->save($user);
			
			$client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
			
			$crawler = $this->isOnPage($client, self::EDIT_ITEM_PATH . '/' . $user->getId());
			
			$form = $crawler->selectButton('submitBtn')->form();
			
			$form['id'] = $user->getId();
			$form['username'] = $user->getUsername();
			$form['name'] = $user->getName();
			$form['surname'] = $user->getSurname();
			$form['email'] = $user->getEmail();
			$form['role_id'] = $user->getRole()->getId();
			$form['is_active'] = 'on';
			
			$client->submit($form);
			
			$this->shouldPageRedirectsTo($client, self::LIST_ITEMS_PATH);
		}
	}