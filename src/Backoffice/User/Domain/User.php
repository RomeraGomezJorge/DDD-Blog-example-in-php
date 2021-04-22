<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Domain;
	
	use DateTimeImmutable;
	use DateTimeInterface;
	use App\Backoffice\Role\Domain\Role;
	use App\Backoffice\User\Domain\Exception\UnavailableUserEmail;
	use App\Backoffice\User\Domain\Exception\UnavailableUsername;
	use App\Backoffice\User\Domain\ValueObject\UserEmail;
	use App\Backoffice\User\Domain\ValueObject\UserName;
	use App\Backoffice\User\Domain\ValueObject\UserPassword;
	use App\Backoffice\User\Domain\ValueObject\UserStatus;
	use App\Backoffice\User\Domain\ValueObject\UserSurname;
	use App\Backoffice\User\Domain\ValueObject\UserUsername;
	use App\Shared\Domain\Aggregate\AggregateRoot;
	use App\Shared\Domain\ValueObject\Uuid;
	use DateTime;
	use Serializable;
	use Symfony\Component\Security\Core\User\UserInterface;
	
	class User extends AggregateRoot implements UserInterface, Serializable
	{
		private $id;
		private string $username;
		private string $name;
		private string $surname;
		private string $email;
		private string $password;
		private Role $role;
		private int $isActive;
		private DateTime $createAt;
		private DateTime $updateAt;
		

		public static function create(
			Uuid $id,
			UserUsername $username,
			UserName $name,
			UserSurname $surname,
			UserEmail $email,
			UserPassword $password,
			Role $role,
			UserStatus $isActive,
			DateTimeInterface $createAt,
			UserNameIsAvailableSpecification $userNameIsAvailableSpecification,
			UserEmailIsAvailableSpecification $userEmailIsAvailableSpecification
		): self {
			$user = new self();
			$user->id = $id->value();
			$user->username = $username->value();
			$user->name = $name->value();
			$user->surname = $surname->value();
			$user->email = $email->value();
			$user->password = $password->value();
			$user->role = $role;
			$user->isActive = $isActive->value();
			$user->createAt = $createAt;
			$user->updateAt = $createAt;
			
			if (!$userNameIsAvailableSpecification->isSatisfiedBy($user)) {
				throw new UnavailableUsername($username->value());
			}
			
			if (!$userEmailIsAvailableSpecification->isSatisfiedBy($user)) {
				throw new UnavailableUserEmail($email->value());
			}
			
			$user->recordThat(UserWasCreated::with($id->value(),
				$username->value(),
				$name->value(),
				$surname->value(),
				$email->value(),
				$password->value(),
				$role->getId(),
				$isActive->__toString()));
			
			return $user;
		}
		

		public function changeDetails(
			UserUsername $username,
			UserName $name,
			UserSurname $surname,
			UserEmail $email,
			Role $role,
			UserStatus $isActive,
			DateTimeInterface $updateAt,
			UserNameIsAvailableSpecification $userNameIsAvailableSpecification,
			UserEmailIsAvailableSpecification $userEmailIsAvailableSpecification
		): self {
			$this->changeUsername($username, $userNameIsAvailableSpecification);
			$this->changeEmail($email, $userEmailIsAvailableSpecification);
			$this->name = $name->value();
			$this->surname = $surname->value();
			$this->role = $role;
			$this->isActive = $isActive->value();
			$this->updateAt = $updateAt;
			return $this;
		}
		
		private function changeUsername(
			UserUsername $newUsername,
			UserNameIsAvailableSpecification $userNameIsAvailableSpecification
		): void {
			if ($newUsername->isEqual($this->username)) {
				return;
			}
			
			$this->username = $newUsername->value();
			
			if (!$userNameIsAvailableSpecification->isSatisfiedBy($this)) {
				throw new UnavailableUsername($newUsername->value());
			}
		}
		
		private function changeEmail(
			UserEmail $newEmail,
			UserEmailIsAvailableSpecification $userEmailIsAvailableSpecification
		): void {
			if ($newEmail->isEqual($this->email)) {
				return;
			}
			
			$this->email = $newEmail->value();
			
			if (!$userEmailIsAvailableSpecification->isSatisfiedBy($this)) {
				throw new UnavailableUserEmail($newEmail->value());
			}
		}
		

		public function updateAccount(
			UserUsername $username,
			UserName $name,
			UserSurname $surname,
			UserEmail $email,
			DateTimeInterface $updateAt,
			UserNameIsAvailableSpecification $userNameIsAvailableSpecification,
			UserEmailIsAvailableSpecification $userEmailIsAvailableSpecification
		): self {
			$this->changeUsername($username, $userNameIsAvailableSpecification);
			$this->changeEmail($email, $userEmailIsAvailableSpecification);
			$this->name = $name->value();
			$this->surname = $surname->value();
			$this->updateAt = $updateAt;
			return $this;
		}
		
		public function encodePassword(string $encodedPassword): void
		{
			$this->password = $encodedPassword;
		}
		
		
		public function setUpdateAt(DateTimeInterface $updateAt): void
		{
			$this->updateAt = $updateAt;
		}
		
		public function getRoles(): array
		{
			return array($this->role->getId());
		}
		
		public function getSalt()
		{
			return null;
		}
		
		public function eraseCredentials(): void
		{
			/*
			 *  meant to clean up possibly stored plain text passwords (or similar credentials).
			 *  Be careful what to erase if your user class is also mapped to a database as the
			 *  modified object will likely be persisted during the request.
			 */
		}
		
		public function serialize()
		{
			return serialize([
				$this->id,
				$this->username,
				$this->password,
				$this->isActive
			]);
		}
		
		public function unserialize($serialized): void
		{
			list($this->id,
				$this->username,
				$this->password,
				$this->isActive
				) = unserialize($serialized);
		}
		
		public function getId(): String
		{
			return $this->id;
		}
		
		public function setId($id): void
		{
			$this->id = $id;
		}
		
		public function getUsername(): string
		{
			return $this->username;
		}
		
		public function getName(): string
		{
			return $this->name;
		}
		
		public function getSurname(): string
		{
			return $this->surname;
		}
		
		public function getEmail(): string
		{
			return $this->email;
		}
		
		public function getPassword(): string
		{
			return $this->password;
		}
		
		public function getRole(): Role
		{
			return $this->role;
		}
		
		public function getIsActive(): int
		{
			return $this->isActive;
		}
	}
