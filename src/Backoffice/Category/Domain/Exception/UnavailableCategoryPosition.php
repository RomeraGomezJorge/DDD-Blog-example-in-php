<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Domain\Exception;
	
	use App\Backoffice\Category\Domain\ValueObject\CategoryPosition;
	use App\Shared\Domain\DomainError;
	
	final class UnavailableCategoryPosition extends DomainError
	{
		private int $position;
		
		public function __construct(CategoryPosition $position)
		{
			$this->position = $position->value();
			
			parent::__construct();
		}
		
		public function errorCode(): string
		{
			return 'position_already_exists';
		}
		
		protected function errorMessage(): string
		{
			return sprintf('La categoria con la posición "%s" ya se encuentra registrada.', $this->position);
		}
	}