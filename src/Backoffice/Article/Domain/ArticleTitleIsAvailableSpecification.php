<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Domain;
	
	class ArticleTitleIsAvailableSpecification
	{
		private ArticleRepository $repository;
		
		public function __construct(ArticleRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function isSatisfiedBy(Article $article): bool
		{
			return $this->repository->isAvailable(array('title' => $article->title()));
		}
	}