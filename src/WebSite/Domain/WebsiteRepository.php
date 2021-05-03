<?php
	
	declare(strict_types=1);
	
	namespace App\WebSite\Domain;
	
	use App\Backoffice\Article\Domain\Article;
	
	interface  WebsiteRepository
	{
		public function findLastedTenArticleFromEachCategory(): ?array;
		
		public function searchBySlug(string $slug): ?Article;
		
		public function searchPreviousArticle(Article $article): ?array;
		
		public function searchNextArticle(Article $article): ?array;
	}