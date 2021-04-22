<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Domain\Exception;
	
	use App\Shared\Domain\DomainError;
	
	final class UserNotExist extends DomainError
	{
		private string $id;
		
		public function __construct(string $id)
		{
			$this->id = $id;
			
			parent::__construct();
		}
		
		public function errorCode(): string
		{
			return 'user_not_exist';
		}
		
		protected function errorMessage(): string
		{
			return sprintf('El usuario con el id "%s" no existe!', $this->id);
		}
	}