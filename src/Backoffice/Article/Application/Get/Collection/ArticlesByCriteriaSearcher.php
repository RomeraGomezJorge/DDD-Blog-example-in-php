<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Application\Get\Collection;
	
	use App\Backoffice\Article\Domain\ArticleRepository;
	use App\Shared\Domain\Criteria\Criteria;
	use App\Shared\Domain\Criteria\Filters;
	use App\Shared\Domain\Criteria\Order;
	
	final class ArticlesByCriteriaSearcher
	{
		private ArticleRepository $repository;
		private FilterUtilsForArticle $filterUtils;
		
		public function __construct(
			ArticleRepository $repository,
			FilterUtilsForArticle $filterUtils
		) {
			$this->repository = $repository;
			$this->filterUtils = $filterUtils;
		}
		
		public function __invoke($filters, $order, $orderBy, ?int $limit, ?int $offset): array
		{
			$category = $this->filterUtils->getCategoryFromFilterOrNull($filters);
			
			$filters = Filters::fromValues(
				$this->filterUtils->removeFiltersThatNotBelongToEntity($filters)
			);
			
			$order = Order::fromValues($order, $orderBy);
			
			$criteria = new Criteria($filters, $order, $offset, $limit);
			
			return $this->repository->matching($criteria, $category);
		}
	}
