<?php
	
	namespace App\Tests\Backoffice\User\Infrastructure\UserInterface\Web;
	
	use App\Tests\Backoffice\User\Domain\UserMother;
	use App\Tests\Backoffice\User\UserInfrastructureTestCase;
	use Symfony\Bundle\FrameworkBundle\KernelBrowser;
	
	final class UserGetControllerTest extends UserInfrastructureTestCase
	{
		private $user;
		/**
		 * @var KernelBrowser
		 */
		private $client;
		
		/** @test */
		public function it_should_find_user()
		{
			$anotherTrafficPoliceBooth = UserMother::randomWithARole($this->roleFound);
			
			$someOtherTrafficPoliceBooth = UserMother::randomWithARole($this->roleFound);
			
			$this->repository()->save($anotherTrafficPoliceBooth);
			$this->repository()->save($someOtherTrafficPoliceBooth);
			$this->clearUnitOfWork();
			
			$this->isOnPage($this->client, self::LIST_ITEMS_PATH);
			
			$this->shouldFindOnThePage($this->client, $this->user->getUsername());
			
			$this->shouldFindOnThePage($this->client, $anotherTrafficPoliceBooth->getEmail());
			
			$this->shouldFindOnThePage($this->client, $someOtherTrafficPoliceBooth->getName());
		}
		
		/** @test */
		public function it_should_show_interface_to_create_a_user()
		{
			$crawler = $this->isOnPage($this->client, self::LIST_ITEMS_PATH);
			
			$this->clickAndFollowTheLink($this->client, $crawler, 'a#createItemLink');
			
			$this->shouldFindOnThePage($this->client, self::LABEL_TO_CREATE_ITEMS);
		}
		
		/** @test */
		public function it_should_find_user_by_criteria()
		{
			$filterByField = 'username';
			
			$anotherUser = UserMother::randomWithARole($this->roleFound);
			
			$this->repository()->save($anotherUser);
			$this->clearUnitOfWork();
			
			$this->isOnPage(
				$this->client,
				self::LIST_ITEMS_PATH . '/page-1/order-createAt-desc/rows_per_page-10/filters%5B0%5D%5Bfield%5D=' . $filterByField . '&filters%5B0%5D%5Boperator%5D=%3D&filters%5B0%5D%5Bvalue%5D=' . $anotherUser->getUsername()
			);
			
			$this->shouldFindOnThePage($this->client, $anotherUser->getUsername());
		}
		
		/** @test */
		public function it_should_show_interface_to_edit_a_user()
		{
			$crawler = $this->isOnPage($this->client, self::LIST_ITEMS_PATH);
			
			$this->clickAndFollowTheLink($this->client, $crawler, 'a.editItemLink');
			
			$this->shouldFindOnThePage($this->client, self::LABEL_TO_UPDATE_ITEMS);
		}
		
		protected function setUp(): void
		{
			parent::setUp();
			
			$this->user = $this->getUserCreatedForTestAndClearUnitOfWork();
			
			$this->repository()->save($this->user);
			
			$this->client = $this->createAuthorizedClientAsAdminAndClearUnitOfWork();
		}
	}