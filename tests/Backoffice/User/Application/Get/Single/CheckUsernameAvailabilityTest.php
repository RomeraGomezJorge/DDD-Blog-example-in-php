<?php
	
	namespace App\Tests\Backoffice\User\Application\Get\Single;
	
	use App\Backoffice\User\Application\Get\Single\CheckUsernameAvailability;
	use App\Tests\Backoffice\User\UserModuleUnitTestCase;
	use App\Tests\Shared\Domain\WordMother;
	
	final class CheckUsernameAvailabilityTest extends UserModuleUnitTestCase
	{
		private CheckUsernameAvailability $usernameChecker;
		
		/** @test */
		public function it_should_return_true_is_username_is_available()
		{
			$availableUsername = WordMother::random();
			
			$criteria = ['username' => $availableUsername];
			
			$this->shouldBeAvailable($criteria);
			
			$isAvailable = $this->usernameChecker->__invoke($availableUsername);
			
			$this->assertEquals($isAvailable, self::USERNAME_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_username_is_unavailable()
		{
			$unavailableUsername = WordMother::random();
			
			$criteria = ['username' => $unavailableUsername];
			
			$this->shouldBeUnavailable($criteria);
			
			$isAvailable = $this->usernameChecker->__invoke($unavailableUsername);
			
			$this->assertEquals($isAvailable, self::USERNAME_IS_NOT_AVAILABLE);
		}
		
		protected function setUp(): void
		{
			$this->usernameChecker = new CheckUsernameAvailability($this->repository());
			
			parent::setUp();
		}
	}