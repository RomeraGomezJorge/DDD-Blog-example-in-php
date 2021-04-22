<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Role\Domain\Exception;
	
	use App\Backoffice\Role\Domain\ValueObject\RoleId;
	use App\Shared\Domain\DomainError;
	
	final class RoleNotExist extends DomainError
	{
		private string $id;
		
		public function __construct( $id)
		{
			$this->id = $id;
			
			parent::__construct();
		}
		
		public function errorCode(): string
		{
			return 'role_not_exist';
		}
		
		protected function errorMessage(): string
		{
			return sprintf('El rol con el id "%s" no existe!', $this->id);
		}
	}