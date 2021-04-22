<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Role\Application\Get;
	
	use App\Backoffice\Role\Domain\Exception\RoleNotExist;
	use App\Backoffice\Role\Domain\Role;
	use App\Backoffice\Role\Domain\RoleRepository;
	use App\Backoffice\Role\Domain\ValueObject\RoleId;
	
	final class RoleFinder
	{
		const NOT_FOUND = null;
		private RoleRepository $repository;
		
		public function __construct(RoleRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function __invoke(string $id): ?Role
		{
			 $id = new RoleId($id);
			
			$role = $this->repository->search($id);
			
			if ( self::NOT_FOUND === $role) {
				throw new RoleNotExist($id->value());
			}
			
			return $role;
		}
	}