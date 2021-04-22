<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Application\Post;
	
	use App\Backoffice\Role\Application\Get\RoleFinder;
	use App\Backoffice\Role\Domain\RoleRepository;
	use App\Backoffice\User\Domain\PasswordEncoder;
	use App\Backoffice\User\Domain\User;
	use App\Backoffice\User\Domain\UserEmailIsAvailableSpecification;
	use App\Backoffice\User\Domain\UserNameIsAvailableSpecification;
	use App\Backoffice\User\Domain\UserRepository;
	use App\Backoffice\User\Domain\ValueObject\UserEmail;
	use App\Backoffice\User\Domain\ValueObject\UserName;
	use App\Backoffice\User\Domain\ValueObject\UserPassword;
	use App\Backoffice\User\Domain\ValueObject\UserStatus;
	use App\Backoffice\User\Domain\ValueObject\UserSurname;
	use App\Backoffice\User\Domain\ValueObject\UserUsername;
	use App\Shared\Domain\Bus\Event\EventBus;
	use App\Shared\Domain\ValueObject\Uuid;
	use DateTime;
	
	final class UserCreator
	{
		private UserRepository $repository;
		private UserNameIsAvailableSpecification $usernameIsAvailableSpecification;
		private UserEmailIsAvailableSpecification  $userEmailIsAvailableSpecification;
		private EventBus $bus;
		private PasswordEncoder $passwordEncoder;
		private RoleFinder $finderRole;
		
		public function __construct(
			UserRepository $repository,
			RoleRepository $roleRepository,
			UserNameIsAvailableSpecification $usernameIsAvailableSpecification,
			UserEmailIsAvailableSpecification $userEmailIsAvailableSpecification,
			PasswordEncoder $passwordEncoder,
			EventBus $bus
		) {
			$this->repository = $repository;
			$this->finderRole = new RoleFinder($roleRepository);
			$this->usernameIsAvailableSpecification = $usernameIsAvailableSpecification;
			$this->userEmailIsAvailableSpecification = $userEmailIsAvailableSpecification;
			$this->passwordEncoder = $passwordEncoder;
			$this->bus = $bus;
		}
		
		public function __invoke(
			string $id,
			string $username,
			string $name,
			string $surname,
			string $email,
			string $plainPassword,
			string $role_id,
			int $isActive
		) {
			
			$plainPassword = new UserPassword($plainPassword);
			
			$role = $this->finderRole->__invoke($role_id);
			
			$createAt = new DateTime();
			
			$user = User::create(
				new Uuid($id),
				new UserUsername($username),
				new UserName($name),
				new UserSurname($surname),
				new UserEmail($email),
				$plainPassword,
				$role,
				new UserStatus($isActive),
				$createAt,
				$this->usernameIsAvailableSpecification,
				$this->userEmailIsAvailableSpecification);
			
			$user->encodePassword(
				$this->passwordEncoder->encode($user, $plainPassword->value())
			);
			
			$this->repository->save($user);
			
			$this->bus->publish(...$user->pullDomainEvents());
		}
	}