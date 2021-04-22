<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Domain;
	
	use App\Backoffice\Role\Domain\Role;
	use App\Shared\Domain\Criteria\Criteria;
	use App\Shared\Domain\ValueObject\Uuid;
	
	interface UserRepository
	{
		public function save(User $user): void;
		
		public function search(Uuid $id): ?User;
		
		public function searchByEmail(string $email): ?User;
		
		public function searchAll(): array;
		
		public function matching(Criteria $criteria, ?Role $rolesFound): array;
		
		public function totalMatchingRows(Criteria $criteria, ?Role $rolesFound): int;
		
		public function delete(User $user): void;
		
		public function isAvailable(array $criteria);
	}