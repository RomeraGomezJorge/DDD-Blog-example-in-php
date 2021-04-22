<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Author\Application\Delete;
	
	use App\Backoffice\Author\Application\Delete\AuthorDeleter;
	use App\Backoffice\Author\Domain\Author;
	use App\Backoffice\Author\Domain\Exception\AuthorNotExist;
	use App\Tests\Backoffice\Author\AuthorModuleUnitTestCase;
	use App\Tests\Backoffice\Author\Domain\AuthorMother;
	
	final class AuthorDeleterTest extends AuthorModuleUnitTestCase
	{
		private AuthorDeleter $deleter;
		private Author $author;
		
		/** @test */
		public function it_should_delete_an_existing_author(): void
		{
			$this->shouldFind($this->author);
			
			$this->repository()
				->shouldReceive('delete')
				->once()
				->with($this->similarTo($this->author));
			
			$this->deleter->__invoke($this->author->id());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_author_does_not_exit(): void
		{
			$this->expectException(AuthorNotExist::class);
			
			$this->shouldNotFind($this->author->id());
			
			$this->repository()
				->shouldReceive('delete')
				->never();
			
			$this->deleter->__invoke($this->author->id());
		}
		
		protected function setUp(): void
		{
			parent::setUp();
			
			$this->deleter = new AuthorDeleter($this->repository());
			
			$this->author = AuthorMother::random();
		}
	}