<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Domain\Exception;
	
	use App\Backoffice\Category\Domain\ValueObject\CategoryDescription;
	use App\Shared\Domain\DomainError;
	
	final class UnavailableCategoryDescription extends DomainError
	{
		private string $description;
		
		public function __construct(CategoryDescription $description)
		{
			$this->description = $description->value();
			
			parent::__construct();
		}
		
		public function errorCode(): string
		{
			return 'description_already_exists';
		}
		
		protected function errorMessage(): string
		{
			return sprintf('El categoria con la descripción "%s" ya se encuentra registrada.', $this->description);
		}
	}