<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Domain\Exception;
	
	use App\Backoffice\Author\Domain\ValueObject\AuthorFullname;
	use App\Shared\Domain\DomainError;
	
	final class UnavailableAuthorFullname extends DomainError
	{
		private string $fullname;
		
		public function __construct(AuthorFullname $fullname)
		{
			$this->fullname = $fullname->value();
			
			parent::__construct();
		}
		
		public function errorCode(): string
		{
			return 'fullname_already_exists';
		}
		
		protected function errorMessage(): string
		{
			return sprintf('El autor con el nombre  "%s" que ha ingresado ya está registrada.', $this->fullname);
		}
	}