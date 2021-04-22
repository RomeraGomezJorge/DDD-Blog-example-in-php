<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Application\Put;
	
	use App\Backoffice\Role\Application\Get\RoleFinder;
	use App\Backoffice\Role\Domain\RoleRepository;
	use App\Backoffice\User\Application\Get\Single\UserFinder;
	use App\Backoffice\User\Domain\UserEmailIsAvailableSpecification;
	use App\Backoffice\User\Domain\UserNameIsAvailableSpecification;
	use App\Backoffice\User\Domain\UserRepository;
	use App\Backoffice\User\Domain\ValueObject\UserEmail;
	use App\Backoffice\User\Domain\ValueObject\UserName;
	use App\Backoffice\User\Domain\ValueObject\UserStatus;
	use App\Backoffice\User\Domain\ValueObject\UserSurname;
	use App\Backoffice\User\Domain\ValueObject\UserUsername;
	use DateTime;
	
	final class UserDetailsChanger
	{
		private UserRepository $repository;
		private UserFinder  $finder;
		private UserNameIsAvailableSpecification $usernameIsAvailableSpecification;
		private UserEmailIsAvailableSpecification  $userEmailIsAvailableSpecification;
		private RoleFinder $roleFinder;
		
		public function __construct(
			UserRepository $repository,
			RoleRepository $roleRepository,
			UserNameIsAvailableSpecification $usernameIsAvailableSpecification,
			UserEmailIsAvailableSpecification $userEmailIsAvailableSpecification
		) {
			$this->repository = $repository;
			$this->finder = new UserFinder($repository);
			$this->roleFinder = new RoleFinder($roleRepository);
			$this->usernameIsAvailableSpecification = $usernameIsAvailableSpecification;
			$this->userEmailIsAvailableSpecification = $userEmailIsAvailableSpecification;
		}
		
		public function __invoke(
			string $id,
			string $newUsername,
			string $newName,
			string $newSurname,
			string $aNewEmail,
			string $role_id,
			int $isActive
		): void {
			$user = $this->finder->__invoke($id);
			
			$newRole = $this->roleFinder->__invoke($role_id);
			
			$user->changeDetails(
				new UserUsername($newUsername),
				new UserName($newName),
				new UserSurname($newSurname),
				new UserEmail($aNewEmail),
				$newRole,
				new UserStatus($isActive),
				new DateTime(),
				$this->usernameIsAvailableSpecification,
				$this->userEmailIsAvailableSpecification
			);
			
			$this->repository->save($user);
		}
	}