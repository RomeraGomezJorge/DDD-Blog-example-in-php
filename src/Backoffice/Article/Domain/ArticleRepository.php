<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Domain;
	
	use App\Backoffice\Category\Domain\Category;
	use App\Shared\Domain\Criteria\Criteria;
	use App\Shared\Domain\ValueObject\Uuid;
	
	interface ArticleRepository
	{
		public function save(Article $article): void;
		
		public function search(Uuid $id): ?Article;
		
		public function searchAll(): array;
		
		public function matching(Criteria $criteria, ?Category $categoryFound): array;
		
		public function totalMatchingRows(Criteria $criteria, ?Category $category): int;
		
		public function delete(Article $article): void;
		
		public function isAvailable(array $criteria): bool;
		
		public function findLastedTenArticleFromEachCategory():?array ;
		
		public function searchBySlug(string $slug):?Article;
		
//		public function searchNextArticle();
	}