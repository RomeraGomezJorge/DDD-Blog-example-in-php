<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Category\Application\Get\Collection;
	
	use App\Backoffice\Category\Application\Get\Collection\CategoriesByCriteriaSearcher;
	use App\Backoffice\Category\Application\Get\Collection\FilterUtilsForCategory;
	use App\Shared\Domain\Criteria\Filters;
	use App\Tests\Backoffice\Category\CategoryModuleUnitTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	use App\Tests\Shared\Domain\Criteria\CriteriaMother;
	
	final class CategoriesByCriteriaSearcherTest extends CategoryModuleUnitTestCase
	{
		private const EMPTY_FILTER = [];
		private $categoriesByCriteriaSearcher;
		
		/** @test */
		public function it_should_search_all_categories()
		{
			$criteria = CriteriaMother::empty();
			
			$this->repository()
				->shouldReceive('matching')
				->once()
				->with($this->similarTo($criteria), self::PARENT_CATEGORY_IS_NOT_DEFINED);
			
			$this->categoriesByCriteriaSearcher->__invoke(
				$criteria->plainFilters(),
				$criteria->order()->orderType()->value(),
				$criteria->order()->orderBy()->value(),
				$criteria->limit(),
				$criteria->offset());
		}
		
		/** @test */
		public function it_should_search_category_by_a_parent_id_criteria()
		{
			$category = CategoryMother::random();
			
			$shouldRemoveParentIdAsFilterBecauseDontBelongToEntity = self::EMPTY_FILTER;
			
			$filters = Filters::fromValues($shouldRemoveParentIdAsFilterBecauseDontBelongToEntity);
			
			$criteria = CriteriaMother::create($filters);
			
			$this->shouldFind($category);
			
			$this->repository()
				->shouldReceive('matching')
				->once()
				->with(
					$this->similarTo($criteria),
					$category);
			
			$parentIdFilter[0]['field'] = 'parent_id';
			$parentIdFilter[0]['operator'] = '=';
			$parentIdFilter[0]['value'] = $category->id();
			
			$this->categoriesByCriteriaSearcher->__invoke(
				$parentIdFilter,
				$criteria->order()->orderType()->value(),
				$criteria->order()->orderBy()->value(),
				$criteria->limit(),
				$criteria->offset());
		}
		
		protected function setUp(): void
		{
			$this->categoriesByCriteriaSearcher = new CategoriesByCriteriaSearcher(
				$this->repository(),
				new FilterUtilsForCategory($this->repository())
			);
			
			parent::setUp();
		}
	}