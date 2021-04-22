<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Domain;
	
	use App\Shared\Domain\Bus\Event\DomainEvent;
	
	final class UserWasCreated extends DomainEvent
	{
		private string $username;
		private string $name;
		private string $surname;
		private string $email;
		private string $password;
		private string $role;
		private string $isActive;
		
		private function __construct(
			string $id,
			string $username,
			string $name,
			string $surname,
			string $email,
			string $password,
			string $role,
			string $isActive,
			string $eventId = null,
			string $occurredOn = null
		) {
			parent::__construct($id, $eventId, $occurredOn);
			$this->username = $username;
			$this->name = $name;
			$this->surname = $surname;
			$this->email = $email;
			$this->password = $password;
			$this->role = $role;
			$this->isActive = $isActive;
		}
		
		public static function with(
			string $id,
			string $username,
			string $name,
			string $surname,
			string $email,
			string $password,
			string $role,
			string $isActive
		): self {
			return new self($id, $username, $name, $surname, $email, $password, $role, $isActive);
		}
		
		public static function eventName(): string
		{
			return 'user.was.created';
		}
		
		public function username(): string
		{
			return $this->username;
		}
		
		public function name(): string
		{
			return $this->name;
		}
		
		public function surname(): string
		{
			return $this->surname;
		}
		
		public function email(): string
		{
			return $this->email;
		}
		
		public function password(): string
		{
			return $this->password;
		}
		
		public function role(): string
		{
			return $this->role;
		}
		
		public function isActive(): string
		{
			return $this->isActive;
		}
	}
