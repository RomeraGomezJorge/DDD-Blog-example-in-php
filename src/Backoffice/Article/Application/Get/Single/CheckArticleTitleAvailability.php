<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Application\Get\Single;
	
	use App\Backoffice\Article\Domain\ArticleRepository;
	
	final class CheckArticleTitleAvailability
	{
		private ArticleRepository $repository;
		
		public function __construct(ArticleRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function __invoke(string $title): bool
		{
			return $this->repository->isAvailable(['title' => trim($title)]);
		}
	}