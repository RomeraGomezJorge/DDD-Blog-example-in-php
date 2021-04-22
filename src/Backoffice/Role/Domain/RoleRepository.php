<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Role\Domain;
	
	use App\Backoffice\Role\Domain\ValueObject\RoleId;
	
	interface RoleRepository
	{
		public function save(Role $id): void;
		
		public function search(RoleId $id): ?Role;
		
		public function searchAll(): array;
	}