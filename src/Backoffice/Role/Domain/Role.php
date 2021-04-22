<?php
	declare(strict_types=1);
	
	namespace App\Backoffice\Role\Domain;
	
	use App\Backoffice\Role\Domain\ValueObject\RoleId;
	use App\Shared\Domain\Aggregate\AggregateRoot;
	use App\Shared\Domain\ValueObject\Uuid;
	
	class Role extends AggregateRoot
	{
		private string $id;
		private string $description;
		
		public static function create(RoleId $id, string $description): self
		{
			$role = new self();
			$role->id = $id->value();
			$role->description = $description;
			
			return $role;
		}
		
		public function getId(): string
		{
			return $this->id;
		}
		
		public function setId(RoleId $id): void
		{
			$this->id = $id->value();
		}
		
		public function description(): string
		{
			return $this->description;
		}
		
		public function setDescription(string $description): void
		{
			$this->description = $description;
		}
	}
