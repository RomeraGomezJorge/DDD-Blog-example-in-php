<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Application\Get\Single;
	
	use App\Backoffice\User\Domain\UserRepository;
	
	final class CheckUsernameAvailability
	{
		private UserRepository $userRepository;
		
		public function __construct(UserRepository $repository)
		{
			$this->userRepository = $repository;
		}
		
		public function __invoke(string $username): bool
		{
			return $this->userRepository->isAvailable(['username' => $username]);
		}
	}