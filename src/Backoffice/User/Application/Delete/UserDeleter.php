<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Application\Delete;
	
	use App\Backoffice\User\Application\Get\Single\UserFinder;
	use App\Backoffice\User\Domain\UserRepository;
	
	final class UserDeleter
	{
		private UserRepository $repository;
		private UserFinder $finder;
		
		public function __construct(
			UserRepository $repository
		) {
			$this->repository = $repository;
			$this->finder = new UserFinder($repository);
		}
		
		public function __invoke(string $id)
		{
			$user = $this->finder->__invoke($id);
			
			$this->repository->delete($user);
		}
	}