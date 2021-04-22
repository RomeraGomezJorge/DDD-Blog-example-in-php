<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Application\Get\Collection;
	
	use App\Backoffice\Category\Domain\CategoryRepository;
	use App\Shared\Domain\Criteria\Criteria;
	use App\Shared\Domain\Criteria\Filters;
	use App\Shared\Domain\Criteria\Order;
	
	final class CategoriesByCriteriaSearcher
	{
		private CategoryRepository $repository;
		private FilterUtilsForCategory $filterUtilsForCategory;
		
		public function __construct(CategoryRepository $repository, FilterUtilsForCategory $filterUtilsForCategory)
		{
			$this->repository = $repository;
			$this->filterUtilsForCategory = $filterUtilsForCategory;
		}
		
		public function __invoke($filters, $order, $orderBy, ?int $limit, ?int $offset): array
		{
			$parent = $this->filterUtilsForCategory->getParentFromFilterOrNull($filters);
			
			$filters = Filters::fromValues(
				$this->filterUtilsForCategory->removeFiltersThatNotBelongToEntity($filters)
			);
			
			$order = Order::fromValues($order, $orderBy);
			
			$criteria = new Criteria($filters, $order, $offset, $limit);
			
			return $this->repository->matching($criteria, $parent);
		}
	}

