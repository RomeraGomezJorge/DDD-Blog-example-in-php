<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\User\Application\Post;
	
	use App\Backoffice\Role\Domain\Exception\RoleNotExist;
	use App\Backoffice\User\Application\Post\UserCreator;
	use App\Backoffice\User\Domain\Exception\UnavailableUserEmail;
	use App\Backoffice\User\Domain\Exception\UnavailableUsername;
	use App\Backoffice\User\Domain\User;
	use App\Tests\Backoffice\User\Domain\UserMother;
	use App\Tests\Backoffice\User\UserModuleUnitTestCase;
	use App\Tests\Shared\Domain\UuidMother;
	use App\Tests\Shared\Domain\WordMother;
	use InvalidArgumentException;
	
	final class UserCreatorTest extends UserModuleUnitTestCase
	{
		private UserCreator $creator;
		private User $user;
		
		/** @test */
		public function it_should_create_a_valid_user(): void
		{
			$this->shouldFindARole($this->user->getRole());
			
			$this->shouldHaveUniqueUserName($this->user);
			
			$this->shouldHaveUniqueEmail($this->user);
			
			$this->shouldEncodePassword($this->user, $this->user->getPassword());
			
			$this->shouldSave($this->user);
			
			$this->shouldPublish();
			
			$this->creator->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getPassword(),
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_username_is_not_available(): void
		{
			$this->expectException(UnavailableUsername::class);
			
			$this->shouldFindARole($this->user->getRole());
			
			$this->shouldNotHaveUniqueUsername($this->user);
			
			$this->shouldEncodePassword($this->user, $this->user->getPassword());
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$this->creator->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getPassword(),
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_email_is_not_available(): void
		{
			$this->expectException(UnavailableUserEmail::class);
			
			$this->shouldFindARole($this->user->getRole());
			
			$this->shouldHaveUniqueUserName($this->user);
			
			$this->shouldNotHaveUniqueEmail($this->user);
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$this->creator->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getPassword(),
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_id_is_not_valid(): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldFindARole($this->user->getRole());
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$this->creator->__invoke(
				UuidMother::invalid(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getPassword(),
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_role_is_not_valid(): void
		{
			$invalidRoleId = 'invalid';
			
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$this->creator->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getPassword(),
				$invalidRoleId,
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_role_is_not_found(): void
		{
			$dontExist = 'ROLE_USER';
			
			$this->expectException(RoleNotExist::class);
			
			$this->shouldNotFindARole($dontExist);
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$this->creator->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$this->user->getPassword(),
				$dontExist,
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_email_is_not_valid(): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldFindARole($this->user->getRole());
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$invalidEmail = WordMother::random();
			
			$this->creator->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$invalidEmail,
				$this->user->getPassword(),
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_password_does_not_have_at_least_1_uppercase_character(
		): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$invalidPasswordWithoutUppercaseCharacters = 'password1234';
			
			$this->creator->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$invalidPasswordWithoutUppercaseCharacters,
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_password_does_not_have_at_least_1_number(): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$invalidPasswordWithoutNumbers = 'Password';
			
			$this->creator->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$invalidPasswordWithoutNumbers,
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_password_does_not_have_at_least_8_characters(): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$invalidPasswordDoesNotHaveAtLeast8Characters = 'Pass123';
			
			$this->creator->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$invalidPasswordDoesNotHaveAtLeast8Characters,
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_password_have_more_than_20_characters(): void
		{
			$this->expectException(InvalidArgumentException::class);
			
			$this->shouldNotSave();
			
			$this->shouldNotPublish();
			
			$invalidPasswordWithHaveMoreThan20Characters = 'Supercalifragilisticoespialidoso123';
			
			$this->creator->__invoke(
				$this->user->getId(),
				$this->user->getUsername(),
				$this->user->getName(),
				$this->user->getSurname(),
				$this->user->getEmail(),
				$invalidPasswordWithHaveMoreThan20Characters,
				$this->user->getRole()->getId(),
				$this->user->getIsActive()
			);
		}
		
		protected function setUp(): void
		{
			parent::setUp(); // TODO: Change the autogenerated stub
			
			$this->creator = new UserCreator(
				$this->repository(),
				$this->roleRepository(),
				$this->usernameIsAvailableSpecification(),
				$this->userEmailIsAvailableSpecification(),
				$this->passwordEncoder(),
				$this->bus());
			
			$this->user = UserMother::random();
		}
	}
