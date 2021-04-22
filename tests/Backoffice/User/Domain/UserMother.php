<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\User\Domain;
	
	use App\Backoffice\Role\Domain\Role;
	use App\Backoffice\Role\Domain\ValueObject\RoleId;
	use App\Backoffice\User\Domain\User;
	use App\Backoffice\User\Domain\UserEmailIsAvailableSpecification;
	use App\Backoffice\User\Domain\UserNameIsAvailableSpecification;
	use App\Backoffice\User\Domain\ValueObject\UserEmail;
	use App\Backoffice\User\Domain\ValueObject\UserName;
	use App\Backoffice\User\Domain\ValueObject\UserPassword;
	use App\Backoffice\User\Domain\ValueObject\UserStatus;
	use App\Backoffice\User\Domain\ValueObject\UserSurname;
	use App\Backoffice\User\Domain\ValueObject\UserUsername;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Shared\Domain\EmailMother;
	use App\Tests\Shared\Domain\IntegerMother;
	use App\Tests\Shared\Domain\PasswordMother;
	use App\Tests\Shared\Domain\WordMother;
	use DateTime;
	use PHPUnit\Framework\TestCase;
	
	
	final class UserMother extends testCase
	{
		public static function random(): User
		{
			return self::create(
				$id = Uuid::random(),
				$username = WordMother::random(),
				$name = WordMother::random(),
				$surname = WordMother::random(),
				$email = EmailMother::random(),
				$password = PasswordMother::random(),
				$role = (new UserMother())->createRandomRole(),
				$isActive = IntegerMother::between(0, 1),
				true,
				true);
		}
		
		public static function create(
			Uuid $id,
			string $username,
			string $name,
			string $surname,
			string $email,
			string $password,
			Role $role,
			int $isActive,
			bool $isUserNameInUse,
			bool $isEmailInUse
		): User {
			$usernameIsAvailableSpecificationStub = (new UserMother())->userNameIsAvailableSpecification();
			
			$usernameIsAvailableSpecificationStub->method('isSatisfiedBy')->willReturn($isUserNameInUse);
			
			$userEmailIsAvailableSpecificationStub = (new UserMother())->userEmailIsAvailableSpecification();
			
			$userEmailIsAvailableSpecificationStub->method('isSatisfiedBy')->willReturn($isEmailInUse);
			
			return User::create(
				$id,
				new UserUsername($username),
				new UserName($name),
				new UserSurname($surname),
				new UserEmail($email),
				new UserPassword($password),
				$role,
				new UserStatus($isActive),
				new DateTime(),
				$usernameIsAvailableSpecificationStub,
				$userEmailIsAvailableSpecificationStub
			);
		}
		
		public function userNameIsAvailableSpecification()
		{
			return $this->createMock(UserNameIsAvailableSpecification::class);
		}
		
		public function userEmailIsAvailableSpecification()
		{
			return $this->createMock(UserEmailIsAvailableSpecification::class);
		}
		
		public static function createRandomRole(): Role
		{
			$roles = array("ROLE_USER", "ROLE_ADMIN");
			
			$role_id = $roles[array_rand($roles, 1)];
			
			$role_id =new RoleId($role_id);
			
			return Role::create(
				$role_id,
				WordMother::random()
			);
		}
		
		public static function randomWithUserName($username): User
		{
			return self::create(
				$id = Uuid::random(),
				$username,
				$name = WordMother::random(),
				$surname = WordMother::random(),
				$email = WordMother::random(),
				$password = WordMother::random(),
				$role = (new UserMother())->createRandomRole(),
				$isActive = IntegerMother::between(0, 1),
				true,
				true);
		}
		
		public static function randomWithEmail($email): User
		{
			return self::create(
				$id = Uuid::random(),
				$username = WordMother::random(),
				$name = WordMother::random(),
				$surname = WordMother::random(),
				$email,
				$password = WordMother::random(),
				$role = (new UserMother())->createRandomRole(),
				$isActive = IntegerMother::between(0, 1),
				true, true);
		}
		
		public static function randomWithARole(Role $role): User
		{
			return self::create(
				$id = Uuid::random(),
				$username = WordMother::random(),
				$name = WordMother::random(),
				$surname = WordMother::random(),
				$email = EmailMother::random(),
				$password = PasswordMother::random(),
				$role,
				$isActive = IntegerMother::between(0, 1),
				
				true,
				true);
		}
		
		public static function randomWithARoleAndUsername(Role $role, string $username): User
		{
			return self::create(
				$id = Uuid::random(),
				$username,
				$name = WordMother::random(),
				$surname = WordMother::random(),
				$email = EmailMother::random(),
				$password = PasswordMother::random(),
				$role,
				$isActive = IntegerMother::between(0, 1),
				
				true,
				true);
		}
		
		public static function createRoleAdmin(): Role
		{
			$role_admin = new RoleId('ROLE_ADMIN');
			
			return Role::create(
				$role_admin,
				WordMother::random()
			);
		}
		
		public static function createRoleUser(): Role
		{
			$role_user = new RoleId('ROLE_USER');
			return Role::create(
				$role_user,
				WordMother::random()
			);
		}
	}
