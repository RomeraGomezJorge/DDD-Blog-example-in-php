<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Domain\ValueObject;
	
	use InvalidArgumentException;
	
	class ArticleState
	{
		const DRAFT = '0';
		const PUBLIC = '1';
		const VALID_STATUSES = [self::PUBLIC, self::DRAFT];
		private $state;
		
		public function __construct( string $state)
		{
			$this->ensureIsAValidState($state);
			
			$this->state = $state;
		}
		
		private function ensureIsAValidState($state): void
		{
			if (!in_array($state, self::VALID_STATUSES)) {
				throw new InvalidArgumentException(sprintf('El valor <%s> no es un estado valido para una publicación.',
					$state));
			}
		}
		
		public static function draft(): self
		{
			return new self(self::DRAFT);
		}
		
		public static function published(): self
		{
			return new self(self::PUBLIC);
		}
		
		public function isPublic(): bool
		{
			return ($this->state == self::PUBLIC);
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