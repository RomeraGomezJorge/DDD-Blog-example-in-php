<?php
	
	declare(strict_types=1);
	
	namespace App\WebSite\Application\Get\Single;
	
	use App\Backoffice\Article\Domain\Article;
	use App\Backoffice\Article\Domain\Exception\ArticleNotExist;
	use App\WebSite\Domain\WebsiteRepository;
	
	final class ArticleFinder
	{
		const NOT_FOUND = null;
		private WebsiteRepository $repository;
		
		public function __construct(WebsiteRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function __invoke(?string $slug):Article
		{
			$articleFound = $this->repository->searchBySlug($slug);
			
			if ($articleFound === self::NOT_FOUND) {
				throw new ArticleNotExist($slug);
			}
			
			return $articleFound;
		}
	}