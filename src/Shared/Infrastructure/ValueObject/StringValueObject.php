<?php
	
	declare(strict_types=1);
	
	namespace App\Shared\Infrastructure\ValueObject;
	
	abstract class StringValueObject extends ValueObject
	{
		protected string $value;
		
		public function __construct(string $value)
		{
			$this->value = $value;
		}
		
		public function value(): string
		{
			return (string)$this->value;
		}
	}