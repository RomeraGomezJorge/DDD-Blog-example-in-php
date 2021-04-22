<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Domain\Exception;
	
	use App\Shared\Domain\DomainError;
	
	final class UnavailableUsername extends DomainError
	{
		private string $username;
		
		public function __construct(string $username)
		{
			$this->username = $username;
			
			parent::__construct();
		}
		
		public function errorCode(): string
		{
			return 'user_name_already_exists';
		}
		
		protected function errorMessage(): string
		{
			return sprintf('El usuario con el nombre de usuario "%s" que ha ingresado ya está registrado.',
				$this->username);
		}
	}