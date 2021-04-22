<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Role\Infrastructure\Persistence;
	
	use App\Backoffice\Role\Domain\Role;
	use App\Backoffice\Role\Domain\RoleRepository;
	use App\Backoffice\Role\Domain\ValueObject\RoleId;
	use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
	
	final class MySqlRoleRepository extends DoctrineRepository implements RoleRepository
	{
		public function save(Role $role): void
		{
			$this->persist($role);
		}
		
		public function search(RoleId $id): ?Role
		{
			return $this->repository(Role::class)->find($id->value());
		}
		
		public function searchAll(): array
		{
			return $this->repository(Role::class)->findAll();
		}
	}