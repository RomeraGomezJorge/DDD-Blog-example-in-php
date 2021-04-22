<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Domain;
	
	class UserNameIsAvailableSpecification
	{
		private UserRepository $repository;
		
		public function __construct(UserRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function isSatisfiedBy(User $user): bool
		{
			return $this->repository->isAvailable(array('username' => $user->getUsername()));
		}
	}