<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Author\Application\Get\Collection;
	
	use App\Backoffice\Author\Application\Get\Collection\AuthorsByCriteriaSearcher;
	use App\Tests\Backoffice\Author\AuthorModuleUnitTestCase;
	use App\Tests\Shared\Domain\Criteria\CriteriaMother;
	
	final class AuthorByCriteriaSearcherTest extends AuthorModuleUnitTestCase
	{
		private AuthorsByCriteriaSearcher $AuthorsByCriteriaSearcher;
		
		/** @test */
		public function it_should_search_author_by_a_criteria()
		{
			$criteria = CriteriaMother::empty();
			
			$this->repository()->shouldReceive('matching')->once()->with($this->similarTo($criteria));
			
			$this->AuthorsByCriteriaSearcher = new AuthorsByCriteriaSearcher($this->repository);
			
			$this->AuthorsByCriteriaSearcher->__invoke(
				$criteria->plainFilters(),
				$criteria->order()->orderType()->value(),
				$criteria->order()->orderBy()->value(),
				$criteria->limit(),
				$criteria->offset());
		}
	}