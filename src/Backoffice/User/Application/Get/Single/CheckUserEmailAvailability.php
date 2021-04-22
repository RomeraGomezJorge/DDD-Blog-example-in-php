<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Application\Get\Single;
	
	use App\Backoffice\User\Domain\UserRepository;
	
	final class CheckUserEmailAvailability
	{
		private UserRepository $repository;
		
		public function __construct(UserRepository $userEmailIsAvailableSpecification)
		{
			$this->repository = $userEmailIsAvailableSpecification;
		}
		
		public function __invoke(string $email): bool
		{
			return $this->repository->isAvailable(['email' => trim($email)]);
		}
	}