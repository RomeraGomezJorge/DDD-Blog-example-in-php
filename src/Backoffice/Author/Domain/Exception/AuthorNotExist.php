<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Domain\Exception;
	
	use App\Shared\Domain\DomainError;
	
	final class AuthorNotExist extends DomainError
	{
		private string $id;
		
		public function __construct(string $id)
		{
			$this->id = $id;
			
			parent::__construct();
		}
		
		public function errorCode(): string
		{
			return 'Author_not_exist';
		}
		
		protected function errorMessage(): string
		{
			return sprintf('La autor con el id "%s" no existe!', $this->id);
		}
	}