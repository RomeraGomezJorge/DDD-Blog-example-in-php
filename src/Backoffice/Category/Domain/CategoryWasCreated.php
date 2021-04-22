<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Domain;
	
	use App\Shared\Domain\Bus\Event\DomainEvent;
	
	final class CategoryWasCreated extends DomainEvent
	{
		private string $description;
		private string $position;
		
		private function __construct(
			string $id,
			string $description,
			string $position,
			string $eventId = null,
			string $occurredOn = null
		) {
			parent::__construct($id, $eventId, $occurredOn);
			$this->description = $description;
			$this->position = $position;
		}
		
		public static function with(
			string $id,
			string $description,
			string $position
		): self {
			return new self($id, $description, $position);
		}
		
		public static function eventName(): string
		{
			return 'category.was.created';
		}
		
		public function description(): string
		{
			return $this->description;
		}
		
		public function position(): string
		{
			return $this->position;
		}
	}
