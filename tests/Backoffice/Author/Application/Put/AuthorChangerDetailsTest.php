<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Author\Application\Put;
	
	
	use App\Backoffice\Author\Application\Put\AuthorChangerDetails;
	use App\Backoffice\Author\Domain\Author;
	use App\Backoffice\Author\Domain\Exception\AuthorNotExist;
	use App\Backoffice\Author\Domain\Exception\UnavailableAuthorFullname;
	use App\Tests\Backoffice\Author\AuthorModuleUnitTestCase;
	use App\Tests\Backoffice\Author\Domain\AuthorMother;
	use App\Tests\Shared\Domain\UuidMother;
	use App\Tests\Shared\Domain\WordMother;
	use InvalidArgumentException;
	
	
	final class AuthorChangerDetailsTest extends AuthorModuleUnitTestCase
	{
		private AuthorChangerDetails $updater;
		private Author $author;
		
		/** @test */
		public function it_should_update_an_existing_when_fullname_is_change(): void
		{
			$newDescription = WordMother::random();
			
			$this->shouldHaveUniqueAuthorFullname($this->author);
			
			$this->shouldGenerateSlug($newDescription);
			
			$this->shouldFind($this->author);
			
			$this->shouldSave($this->author);
			
			$this->updater->__invoke($this->author->id(), $newDescription, $this->author->biography());
		}
		
		/** @test */
		public function it_should_not_update_an_existing_fullname_if_it_does_not_change(): void
		{
			$this->authorFullnameIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->never();
			$this->shouldGenerateSlug($this->author->fullname());
			
			$this->shouldFind($this->author);
			
			$this->shouldSave($this->author);
			
			$this->updater->__invoke($this->author->id(), $this->author->fullname(), $this->author->biography());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_author_does_not_exit(): void
		{
			$this->expectException(AuthorNotExist::class);
			
			$this->shouldNotFind($this->author->id());
			
			$this->shouldNotSave();
			
			$this->updater->__invoke($this->author->id(), $this->author->fullname(), $this->author->biography());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_fullname_is_not_available(): void
		{
			$unavailableFullname = WordMother::random();
			
			$this->expectException(UnavailableAuthorFullname::class);
			
			$this->shouldNotHaveUniqueAuthorFullname($this->author);
			
			$this->shouldFind($this->author);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke($this->author->id(), $unavailableFullname, $this->author->biography());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_id_is_not_valid(): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke(UuidMother::invalid(), $this->author->fullname(), $this->author->biography());
		}
		
		protected function setUp(): void
		{
			parent::setUp();
			
			$this->updater = new AuthorChangerDetails(
				$this->repository(),
				$this->authorFullnameIsAvailableSpecification(),
				$this->slugGenerator()
			);
			
			$this->author = AuthorMother::random();
		}
	}

