<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Domain\Exception;
	
	use App\Backoffice\Category\Domain\ValueObject\CategoryName;
	use App\Shared\Domain\DomainError;
	
	final class UnavailableCategoryName extends DomainError
	{
		private string $name;
		
		public function __construct(CategoryName $name)
		{
			$this->name = $name->value();
			
			parent::__construct();
		}
		
		public function errorCode(): string
		{
			return 'name_already_exists';
		}
		
		protected function errorMessage(): string
		{
			return sprintf('El categoria con el nombre "%s" ya se encuentra registrada.', $this->name);
		}
	}