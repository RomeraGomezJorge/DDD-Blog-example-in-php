<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Domain\Exception;
	
	use App\Shared\Domain\DomainError;
	
	final class UnavailableUserEmail extends DomainError
	{
		private string $email;
		
		public function __construct(string $email)
		{
			$this->email = $email;
			
			parent::__construct();
		}
		
		public function errorCode(): string
		{
			return 'email_already_exists';
		}
		
		protected function errorMessage(): string
		{
			return sprintf('El usuario con el correo electronico "%s" que ha ingresado ya está registrado.',
				$this->email);
		}
	}