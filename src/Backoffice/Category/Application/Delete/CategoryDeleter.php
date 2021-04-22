<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Application\Delete;
	
	use App\Backoffice\Category\Application\Get\Single\CategoryFinder;
	use App\Backoffice\Category\Domain\CategoryRepository;
	
	final class CategoryDeleter
	{
		private CategoryRepository $repository;
		private CategoryFinder $finder;
		
		public function __construct(
			CategoryRepository $repository
		) {
			$this->repository = $repository;
			$this->finder = new CategoryFinder($repository);
		}
		
		public function __invoke(string $id)
		{
			$categoryFound = $this->finder->__invoke($id);
			
			$this->repository->delete($categoryFound);
		}
	}