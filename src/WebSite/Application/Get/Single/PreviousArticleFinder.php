<?php
	
	declare(strict_types=1);
	
	namespace App\WebSite\Application\Get\Single;
	
	use App\Backoffice\Article\Domain\Article;
	use App\WebSite\Domain\WebsiteRepository;
	
	final class PreviousArticleFinder
	{
		private WebsiteRepository $repository;
		
		public function __construct(WebsiteRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function __invoke(Article $article):?array
		{
			return $this->repository->searchPreviousArticle($article);
		}
	}