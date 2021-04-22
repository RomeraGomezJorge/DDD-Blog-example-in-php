<?php
	
	namespace App\Tests\Backoffice\User\Application\Get\Single;
	
	use App\Backoffice\User\Application\Get\Single\CheckUserEmailAvailability;
	use App\Tests\Backoffice\User\UserModuleUnitTestCase;
	use App\Tests\Shared\Domain\EmailMother;
	
	
	final class CheckUserEmailAvailabilityTest extends UserModuleUnitTestCase
	{
		private CheckUserEmailAvailability $emailChecker;
		
		/** @test */
		public function it_should_return_true_is_email_is_available()
		{
			$availableEmail = EmailMother::random();
			
			$criteria = ['email' => $availableEmail];
			
			$this->shouldBeAvailable($criteria);
			
			$isAvailable = $this->emailChecker->__invoke($availableEmail);
			
			$this->assertEquals($isAvailable, self::EMAIL_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_email_is_unavailable()
		{
			$unavailableEmail = EmailMother::random();
			
			$criteria = ['email' => $unavailableEmail];
			
			$this->shouldBeUnavailable($criteria);
			
			$isAvailable = $this->emailChecker->__invoke($unavailableEmail);
			
			$this->assertEquals($isAvailable, self::EMAIL_IS_NOT_AVAILABLE);
		}
		
		protected function setUp(): void
		{
			$this->emailChecker = new CheckUserEmailAvailability($this->repository());
			
			parent::setUp();
		}
	}