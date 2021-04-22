<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Domain;
	
	use App\Shared\Domain\Bus\Event\DomainEvent;
	
	final class AuthorWasCreated extends DomainEvent
	{
		private string $fullname;
		private string $biography;
		
		private function __construct(
			string $id,
			string $username,
			string $biography,
			string $eventId = null,
			string $occurredOn = null
		) {
			parent::__construct($id, $eventId, $occurredOn);
			$this->fullname = $username;
			$this->biography = $biography;
		}
		
		public static function with(string $id, string $fullname, string $biography): self
		{
			return new self($id, $fullname, $biography);
		}
		
		public static function eventName(): string
		{
			return 'author.was.created';
		}
		
		public function fullname(): string
		{
			return $this->fullname;
		}
		
		public function biography(): void
		{
			$this->biography;
		}
	}
