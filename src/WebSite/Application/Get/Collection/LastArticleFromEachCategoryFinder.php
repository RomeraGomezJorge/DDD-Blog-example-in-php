<?php
	
	declare(strict_types=1);
	
	namespace App\WebSite\Application\Get\Collection;
	
	use App\Backoffice\Article\Domain\ArticleRepository;
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\CategoryRepository;
	use App\WebSite\Domain\WebsiteRepository;
	
	final class LastArticleFromEachCategoryFinder
	{
		private WebsiteRepository $repository;
		private CategoryRepository $categoryRepository;
		
		public function __construct( WebsiteRepository $repository, CategoryRepository $categoryRepository)
		{
			$this->repository = $repository;
			$this->categoryRepository = $categoryRepository;
		}
		
		public function __invoke():array
		{
			$articles = $this->repository->findLastedTenArticleFromEachCategory();
			
			$categories = $this->categoryRepository->getAllParentCategoriesSortedByPosition();
			
			$articlesInCategory = [];
			
			foreach( $categories as $category) {
				
				foreach ($articles as $article){
					
					if ( !$this->isArticleCategoryEqualTo($article, $category->id())) {
						continue;
					}

					if ( !$this->isMainArticleSet($articlesInCategory, $category)) {
						$articlesInCategory[$category->name()]['mainArticle'] = $article;
						continue;
					}

					$articlesInCategory[$category->name()]['slideArticles'][] = $article;
				}
				
			}
			
			return $articlesInCategory;
		}
		
		
		private function isMainArticleSet(array $articlesInCategory,Category $category): bool
		{
			return isset($articlesInCategory[$category->name()]['mainArticle']);
		}
		
		private function isArticleCategoryEqualTo($article, string $categoryId): bool
		{
			return $article['category_id'] == $categoryId;
		}
	}