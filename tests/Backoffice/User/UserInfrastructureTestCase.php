<?php
	
	namespace App\Tests\Backoffice\User;
	
	use App\Backoffice\Role\Domain\Role;
	use App\Backoffice\Role\Domain\RoleRepository;
	use App\Backoffice\User\Domain\User;
	use App\Backoffice\User\Domain\UserRepository;
	use App\Tests\Backoffice\User\Domain\UserMother;
	use App\Tests\Shared\Infrastructure\PhpUnit\ContextInfrastructureTestCase;
	
	class UserInfrastructureTestCase extends ContextInfrastructureTestCase
	{
		const LIST_ITEMS_PATH = '/backoffice/user/list';
		const CREATE_ITEM_PATH = '/backoffice/user/create';
		const EDIT_ITEM_PATH = '/backoffice/user/edit';
		const LABEL_TO_CREATE_ITEMS = 'Crear Usuarios';
		const LABEL_TO_UPDATE_ITEMS = 'Actualizar Usuarios';
		protected Role $roleFound;
		
		protected function repository(): UserRepository
		{
			return $this->service(UserRepository::class);
		}
		
		protected function roleRepository(): RoleRepository
		{
			return $this->service(RoleRepository::class);
		}
		
		protected function getUserCreatedForTestAndClearUnitOfWork(): User
		{
			$this->roleFound = $this->getARoleAdminFromDatabase();
			
			return UserMother::randomWithARole($this->roleFound);
		}
	}