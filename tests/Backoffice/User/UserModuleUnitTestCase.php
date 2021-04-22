<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\User;
	
	
	use App\Backoffice\Role\Domain\Role;
	use App\Backoffice\Role\Domain\RoleRepository;
	use App\Backoffice\Role\Domain\ValueObject\RoleId;
	use App\Backoffice\TrafficPoliceBooth\Domain\TrafficPoliceBooth;
	use App\Backoffice\TrafficPoliceBooth\Domain\TrafficPoliceBoothRepository;
	use App\Backoffice\User\Domain\PasswordEncoder;
	use App\Backoffice\USer\Domain\User;
	use App\Backoffice\User\Domain\UserEmailIsAvailableSpecification;
	use App\Backoffice\USer\Domain\UserNameIsAvailableSpecification;
	use App\Backoffice\USer\Domain\UserRepository;
	use App\Shared\Domain\Bus\Event\EventBus;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
	
	
	abstract class UserModuleUnitTestCase extends UnitTestCase
	{
		const USERNAME_IS_AVAILABLE = true;
		const USERNAME_IS_NOT_AVAILABLE = false;
		const EMAIL_IS_AVAILABLE = true;
		const EMAIL_IS_NOT_AVAILABLE = false;
		const AVAILABLE = true;
		const UNAVAILABLE = false;
		protected $repository;
		protected $usernameIsAvailableSpecification;
		protected $userEmailIsAvailableSpecification;
		protected $bus;
		private $passwordEncoder;
		private $roleRepository;
		
		protected function roleRepository(): RoleRepository
		{
			return $this->roleRepository = $this->roleRepository ?: $this->mock(RoleRepository::class);
		}
		
		protected function userNameIsAvailableSpecification(): UserNameIsAvailableSpecification
		{
			return $this->usernameIsAvailableSpecification = $this->usernameIsAvailableSpecification ?: $this->mock(UserNameIsAvailableSpecification::class);
		}
		
		protected function userEmailIsAvailableSpecification(): UserEmailIsAvailableSpecification
		{
			return $this->userEmailIsAvailableSpecification = $this->userEmailIsAvailableSpecification ?: $this->mock(UserEmailIsAvailableSpecification::class);
		}
		
		public function shouldHaveUniqueUserName(User $user): void
		{
			$this->usernameIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->once()
				->with($this->similarTo($user))
				->andReturn(self::USERNAME_IS_AVAILABLE);
		}
		
		public function shouldNotHaveUniqueUsername(User $user): void
		{
			$this->usernameIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->once()
				->with($this->similarTo($user))
				->andReturn(self::USERNAME_IS_NOT_AVAILABLE);
		}
		
		public function shouldHaveUniqueEmail(User $user): void
		{
			$this->userEmailIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->once()
				->with($this->similarTo($user))
				->andReturn(self::EMAIL_IS_AVAILABLE);
		}
		
		public function shouldNotHaveUniqueEmail(User $user): void
		{
			$this->userEmailIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->once()
				->with($this->similarTo($user))
				->andReturn(self::EMAIL_IS_NOT_AVAILABLE);
		}
		
		protected function shouldFind(User $user): void
		{
			$id = new Uuid($user->getId());
			$this->repository()
				->shouldReceive('search')
				->once()
				->with($this->similarTo($id))
				->andReturn($user);
		}
		
		protected function repository(): UserRepository
		{
			return $this->repository = $this->repository ?: $this->mock(UserRepository::class);
		}
		
		protected function shouldNotFind($id): void
		{
			$id = new Uuid($id);
			
			$this->repository()
				->shouldReceive('search')
				->once()
				->with($this->similarTo($id))
				->andReturnNull();
		}
		
		protected function shouldFindARole(Role $role): void
		{
			$roleId = new RoleId($role->getId());
			$this->roleRepository()
				->shouldReceive('search')
				->once()
				->with($this->similarTo($roleId))
				->andReturn($role);
		}
		
		protected function shouldNotFindARole(string $roleId): void
		{
			$roleId = new RoleId($roleId);
			$this->roleRepository()
				->shouldReceive('search')
				->once()
				->with($this->similarTo($roleId))
				->andReturnNull();
		}
		
		protected function shouldSave(User $user): void
		{
			$this->repository()
				->shouldReceive('save')
				->once()
				->with($this->similarTo($user))
				->andReturnNull();
		}
		
		protected function shouldNotSave()
		{
			$this->repository()
				->shouldReceive('save')
				->never();
		}
		
		protected function shouldPublish()
		{
			$this->bus()
				->shouldReceive('publish')
				->once();
		}
		
		protected function bus(): EventBus
		{
			return $this->bus = $this->bus ?: $this->mock(EventBus::class);
		}
		
		protected function shouldNotPublish()
		{
			$this->bus()
				->shouldReceive('publish')
				->never();
		}
		
		protected function shouldEncodePassword(User $user, string $password)
		{
			$this->passwordEncoder()->shouldReceive('encode')->with($this->similarTo($user),
				$password)->andReturn($password);
		}
		
		protected function passwordEncoder(): PasswordEncoder
		{
			return $this->passwordEncoder = $this->passwordEncoder ?: $this->mock(PasswordEncoder::class);
		}
		
		protected function shouldBeAvailable(array $criteria): void
		{
			$this->repository()
				->shouldReceive('isAvailable')
				->once()
				->with($criteria)
				->andReturn(self::AVAILABLE);
		}
		
		protected function shouldBeUnavailable(array $criteria): void
		{
			$this->repository()
				->shouldReceive('isAvailable')
				->once()
				->with($criteria)
				->andReturn(self::UNAVAILABLE);
		}
	}
