<?php
	
	
	namespace App\Backoffice\Role\Domain\ValueObject;
	
	
	use InvalidArgumentException;
	
	final class RoleId
	{
		const ADMIN = 'ROLE_ADMIN';
		const USER = 'ROLE_USER';
		const VALID_ROLES = [self::USER, self::ADMIN];
		private $state;
		
		public function __construct( string $state)
		{
			$this->ensureIsAValidRole($state);
			
			$this->state = $state;
		}
		
		private function ensureIsAValidRole($state): void
		{
			if (!in_array($state, self::VALID_ROLES)) {
				throw new InvalidArgumentException(sprintf('El valor <%s> no es un rol válido para un usuario.',
					$state));
			}
		}
		
		public static function admin(): self
		{
			return new self(self::ADMIN);
		}
		
		public static function user(): self
		{
			return new self(self::USER);
		}
		
		public function isAdmin(): bool
		{
			return ($this->state == self::ADMIN);
		}
		
		public function __toString(): string
		{
			return (string)$this->value();
		}
		
		public function value(): string
		{
			return $this->state;
		}
	}