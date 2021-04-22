<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Application\Delete;
	
	use App\Backoffice\Author\Application\Get\Single\AuthorFinder;
	use App\Backoffice\Author\Domain\AuthorRepository;
	
	final class AuthorDeleter
	{
		private AuthorRepository $repository;
		private AuthorFinder $finder;
		
		public function __construct(
			AuthorRepository $repository
		) {
			$this->repository = $repository;
			$this->finder = new AuthorFinder($repository);
		}
		
		public function __invoke(string $id)
		{
			$Author = $this->finder->__invoke($id);
			
			$this->repository->delete($Author);
		}
	}