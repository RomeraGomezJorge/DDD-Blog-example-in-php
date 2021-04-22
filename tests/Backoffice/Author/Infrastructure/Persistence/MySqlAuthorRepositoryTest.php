<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Author\Infrastructure\Persistence;
	
	use App\Backoffice\Author\Domain\Author;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Backoffice\Author\AuthorInfrastructureTestCase;
	use App\Tests\Backoffice\Author\Domain\AuthorMother;
	use App\Tests\Shared\Domain\Criteria\CriteriaMother;
	use App\Tests\Shared\Domain\WordMother;
	
	final class MySqlAuthorRepositoryTest extends AuthorInfrastructureTestCase
	{
		const FULLNAME_IS_AVAILABLE = true;
		const FULLNAME_IS_NOT_AVAILABLE = false;
		private Author $author;
		private Uuid $id;
		
		/** @test */
		public function it_should_save_a_author(): void
		{
			
			$this->repository()->save($this->author);
			
			$this->clearUnitOfWork();
			
			$this->assertNotNull($this->repository()->search($this->id));
		}
		
		/** @test */
		public function it_should_return_an_existing_author(): void
		{
			$this->repository()->save($this->author);
			
			$this->clearUnitOfWork();
			
			$authorFound = $this->repository()->search($this->id);
			
			
			$this->assertEquals($this->author->id(), $authorFound->id());
			$this->assertEquals($this->author->fullname(), $authorFound->fullname());
			$this->assertEquals($this->author->biography(), $authorFound->biography());
		}
		
		/** @test */
		public function it_should_not_return_a_non_existing_author(): void
		{
			$this->assertNull(
				$this->repository()->search($this->id)
			);
		}
		
		/** @test */
		public function it_should_delete_an_existing_author()
		{
			$this->repository()->save($this->author);
			
			$this->clearUnitOfWork();
			
			$itemFound = $this->repository()->search($this->id);
			
			$this->assertNull(
				$this->repository()->delete($itemFound)
			);
		}
		
		/** @test */
		public function it_should_search_all_existing_author(): void
		{
			$existingAuthor = AuthorMother::random();
			$anotherExistingAuthor = AuthorMother::random();
			$existingAuthors = [$existingAuthor, $anotherExistingAuthor];
			
			$this->repository()->save($existingAuthor);
			$this->repository()->save($anotherExistingAuthor);
			$this->clearUnitOfWork();
			
			$this->assertEquals(
				count($existingAuthors),
				count($this->repository()->searchAll())
			);
		}
		
		/** @test */
		public function it_should_search_all_existing_authors_with_an_empty_criteria(): void
		{
			$existingItem = AuthorMother::random();
			$anotherExistingItem = AuthorMother::random();
			$existingItems = [$existingItem, $anotherExistingItem];
			
			$this->repository()->save($existingItem);
			$this->repository()->save($anotherExistingItem);
			$this->clearUnitOfWork();
			
			$this->assertEquals(
				count($existingItems),
				count($this->repository()->matching(CriteriaMother::empty()))
			);
		}
		
		/** @test */
		public function it_should_filter_by_criteria(): void
		{
			$dddInPhpItem = AuthorMother::randomWithFullname('DDD en PHP');
			$dddInJavaItem = AuthorMother::randomWithFullname('DDD en Java');
			$intellijItem = AuthorMother::randomWithFullname('Exprimiendo Intellij');
			$dddAuthors = [$dddInPhpItem, $dddInJavaItem];
			
			$fullNameContainsDddCriteria = CriteriaMother::contains('fullname', 'DDD');
			
			$this->repository()->save($dddInJavaItem);
			$this->repository()->save($dddInPhpItem);
			$this->repository()->save($intellijItem);
			$this->clearUnitOfWork();
			
			$this->assertEquals(
				count($dddAuthors),
				count($this->repository()->matching($fullNameContainsDddCriteria))
			);
		}
		
		/** @test */
		public function it_should_return_true_is_author_fullname_is_available()
		{
			$this->repository()->save($this->author);
			
			$availableFullname = WordMother::random();
			
			$authorCriteria = ['fullname' => $availableFullname];
			
			$isAvailable = $this->repository()->isAvailable($authorCriteria);
			
			$this->assertEquals($isAvailable, self::FULLNAME_IS_AVAILABLE);
		}
		
		/** @test */
		public function it_should_return_false_is_author_fullname_is_unavailable()
		{
			$this->repository()->save($this->author);
			
			$unavailableFullname = $this->author->fullname();
			
			$authorCriteria = ['fullname' => $unavailableFullname];
			
			$isAvailable = $this->repository()->isAvailable($authorCriteria);
			
			$this->assertEquals($isAvailable, self::FULLNAME_IS_NOT_AVAILABLE);
		}
		
		protected function setUp(): void
		{
			parent::setUp();
			
			$this->author = AuthorMother::random();
			
			$this->id = new Uuid($this->author->id());
		}
	}
