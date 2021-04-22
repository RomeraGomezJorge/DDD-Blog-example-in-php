<?php
	
	declare(strict_types=1);
	
	namespace App\Shared\Infrastructure\ValueObject;
	
	abstract class IntValueObject extends ValueObject
	{
		protected int $value;
		
		public function __construct(int $value)
		{
			$this->value = $value;
		}
		
		public function value(): int
		{
			return $this->value;
		}
	}