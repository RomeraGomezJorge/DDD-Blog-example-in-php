<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Application\Get\Single;
	
	use App\Backoffice\User\Domain\Exception\UserNotExist;
	use App\Backoffice\User\Domain\User;
	use App\Backoffice\User\Domain\UserRepository;
	use App\Shared\Domain\ValueObject\Uuid;
	
	final class UserFinder
	{
		const NOT_FOUND = null;
		private UserRepository $repository;
		
		public function __construct(UserRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function __invoke(string $id): ?User
		{
			$id = new Uuid($id);
			
			$user = $this->repository->search($id);
			
			if ($user === self::NOT_FOUND) {
				throw new UserNotExist($id->value());
			}
			
			return $user;
		}
	}