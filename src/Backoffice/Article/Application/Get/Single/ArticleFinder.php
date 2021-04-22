<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Application\Get\Single;
	
	use App\Backoffice\Article\Domain\Article;
	use App\Backoffice\Article\Domain\ArticleRepository;
	use App\Backoffice\Article\Domain\Exception\ArticleNotExist;
	use App\Shared\Domain\ValueObject\Uuid;
	
	final class ArticleFinder
	{
		const NOT_FOUND = null;
		private ArticleRepository $repository;
		
		public function __construct(ArticleRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function __invoke(string $id): Article
		{
			$id = new Uuid($id);
			
			$article = $this->repository->search($id);
			
			if ($article === self::NOT_FOUND) {
				throw new ArticleNotExist($id);
			}
			
			return $article;
		}
	}