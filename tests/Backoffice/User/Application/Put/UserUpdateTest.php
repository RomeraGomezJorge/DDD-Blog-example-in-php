<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\User\Application\Put;
	
	use App\Backoffice\Role\Domain\Exception\RoleNotExist;
	use App\Backoffice\User\Application\Put\UserDetailsChanger;
	use App\Backoffice\User\Domain\Exception\UnavailableUserEmail;
	use App\Backoffice\User\Domain\Exception\UnavailableUsername;
	use App\Backoffice\User\Domain\Exception\UserNotExist;
	use App\Backoffice\User\Domain\User;
	use App\Tests\Backoffice\User\Domain\UserMother;
	use App\Tests\Backoffice\User\UserModuleUnitTestCase;
	use App\Tests\Shared\Domain\EmailMother;
	use App\Tests\Shared\Domain\UuidMother;
	use App\Tests\Shared\Domain\WordMother;
	use InvalidArgumentException;
	
	final class UserUpdateTest extends UserModuleUnitTestCase
	{
		private UserDetailsChanger $updater;
		private User $user;
		
		/** @test */
		public function it_should_update_an_existing_when_username_is_change(): void
		{
			$newUserName = WordMother::random();
			
			$this->shouldHaveUniqueUserName($this->user);
			
			$this->shouldFindARole($this->user->getRole());
			
			$this->shouldFind($this->user);
			
			$this->shouldSave($this->user);
			
			$this->updater->__invoke(
				$this->user->getId(),
				$newUserName,
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_not_update_an_existing_username_if_it_does_not_change(): void
		{
			$this->usernameIsAvailableSpecification()->shouldReceive('isSatisfiedBy')->never();
			
			$this->shouldFindARole($this->user->getRole());
			
			$this->shouldFind($this->user);
			
			$this->shouldSave($this->user);
			
			$this->updater->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_update_an_existing_when_email_is_change(): void
		{
			$newEmail = EmailMother::random();
			
			$this->shouldFindARole($this->user->getRole());
			
			$this->shouldHaveUniqueEmail($this->user);
			
			$this->shouldFind($this->user);
			
			$this->shouldSave($this->user);
			
			$this->updater->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$newEmail,
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_not_update_an_existing_email_if_it_does_not_change(): void
		{
			$this->shouldFindARole($this->user->getRole());
			
			$this->userEmailIsAvailableSpecification()->shouldReceive('isSatisfiedBy')->never();
			
			$this->shouldFind($this->user);
			
			$this->shouldSave($this->user);
			
			$this->updater->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getRole()->getId(),
				$this->user->getIsActive(),
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_user_does_not_exit(): void
		{
			$this->expectException(UserNotExist::class);
			
			$this->shouldNotFind($this->user->getId());
			
			$this->shouldNotSave();
			
			$this->updater->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_username_is_not_available(): void
		{
			$usernameInUse = WordMother::random();
			
			$this->shouldFindARole($this->user->getRole());
			
			$this->expectException(UnavailableUsername::class);
			
			$this->shouldNotHaveUniqueUsername($this->user);
			
			$this->shouldFind($this->user);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke(
				$this->user->getId(),
				$usernameInUse,
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_email_is_not_available(): void
		{
			$emailInUse = EmailMother::random();
			
			$this->expectException(UnavailableUserEmail::class);
			
			$this->shouldFindARole($this->user->getRole());
			
			$this->shouldNotHaveUniqueEmail($this->user);
			
			$this->shouldFind($this->user);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$emailInUse,
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_id_is_not_valid(): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldNotSave();
			
			$this->updater->__invoke(
				UuidMother::invalid(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_role_is_not_found(): void
		{
			$roleIdDoesNotExist = 'ROLE_USER';
			
			$this->expectException(RoleNotExist::class);
			
			$this->shouldFind($this->user);
			
			$this->shouldNotFindARole($roleIdDoesNotExist);
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$this->updater->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$roleIdDoesNotExist,
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_role_is_not_valid(): void
		{
			$invalidRoleId = 'invalid';
			
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldFind($this->user);
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$this->updater->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$invalidRoleId,
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_email_is_not_valid(): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldFind($this->user);
			
			$this->shouldFindARole($this->user->getRole());
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$invalidEmail = WordMother::random();
			
			$this->updater->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$invalidEmail,
				$this->user->getRole()->getId(),
				$this->user->getIsActive());
		}
		
		protected function setUp(): void
		{
			parent::setUp(); // TODO: Change the autogenerated stub
			
			$this->updater = new UserDetailsChanger(
				$this->repository(),
				$this->roleRepository(),
				$this->usernameIsAvailableSpecification(),
				$this->userEmailIsAvailableSpecification()
			);
			
			$this->user = userMother::random();
		}
	}

