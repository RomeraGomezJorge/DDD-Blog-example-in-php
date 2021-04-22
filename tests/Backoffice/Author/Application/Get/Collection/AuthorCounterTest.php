<?php
	
	namespace App\Tests\Backoffice\Author\Application\Get\Collection;
	
	use App\Backoffice\Author\Application\Get\Collection\AuthorByCriteriaCounter;
	use App\Tests\Backoffice\Author\AuthorModuleUnitTestCase;
	use App\Tests\Shared\Domain\Criteria\CriteriaMother;
	
	final class AuthorCounterTest extends AuthorModuleUnitTestCase
	{
		/** @test */
		public function it_should_count_all_author()
		{
			$criteria = CriteriaMother::empty();
			
			$this->repository()->shouldReceive('totalMatchingRows')->once()->with($this->similarTo($criteria));
			
			$authorByCriteriaCounter = new AuthorByCriteriaCounter($this->repository());
			
			$authorByCriteriaCounter->__invoke(
				$criteria->plainFilters(),
				$criteria->order()->orderType()->value(),
				$criteria->order()->orderBy()->value(),
				$criteria->limit(),
				$criteria->offset());
		}
	}