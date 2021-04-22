<?php
	
	
	namespace App\Tests\Backoffice\Author\Application\Get\Single;
	
	use App\Backoffice\Author\Application\Get\Single\CheckAuthorFullnameAvailability;
	use App\Backoffice\Author\Domain\Author;
	use App\Tests\Backoffice\Author\AuthorModuleUnitTestCase;
	use App\Tests\Backoffice\Author\Domain\AuthorMother;
	use App\Tests\Shared\Domain\WordMother;
	
	final class CheckAuthorFullnameAvailabilityTest extends AuthorModuleUnitTestCase
	{
		private CheckAuthorFullnameAvailability $descriptionChecker;
		private Author $author;
		
		/** @test */
		public function it_should_return_true_is_author_fullname_is_available()
		{
			$availableFullname = WordMother::random();
			
			$criteria = ['fullname' => $availableFullname];
			
			$this->shouldBeAvailable($criteria);
			
			$isAvailable = $this->descriptionChecker->__invoke($availableFullname);
			
			$this->assertEquals($isAvailable, self::FULLNAME_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_author_fullname_is_unavailable()
		{
			$unavailableFullname = WordMother::random();
			
			$criteria = ['fullname' => $unavailableFullname];
			
			$this->shouldBeUnavailable($criteria);
			
			$isAvailable = $this->descriptionChecker->__invoke($unavailableFullname);
			
			$this->assertEquals($isAvailable, self::FULLNAME_IS_NOT_AVAILABLE);
		}
		
		protected function setUp(): void
		{
			$this->descriptionChecker = new CheckAuthorFullnameAvailability($this->repository());
			
			parent::setUp();
			
			$this->author = AuthorMother::random();
		}
	}