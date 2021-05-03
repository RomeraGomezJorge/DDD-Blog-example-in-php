<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Domain;
	
	use App\Shared\Domain\Bus\Event\DomainEvent;
	
	final class CategoryWasCreated extends DomainEvent
	{
		private string $name;
		private string $position;
		
		private function __construct(
			string $id,
			string $name,
			string $position,
			string $eventId = null,
			string $occurredOn = null
		) {
			parent::__construct($id, $eventId, $occurredOn);
			$this->name = $name;
			$this->position = $position;
		}
		
		public static function with(
			string $id,
			string $name,
			string $position
		): self {
			return new self($id, $name, $position);
		}
		
		public static function eventName(): string
		{
			return 'category.was.created';
		}
		
		public function name(): string
		{
			return $this->name;
		}
		
		public function position(): string
		{
			return $this->position;
		}
	}
