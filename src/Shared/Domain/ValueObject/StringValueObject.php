<?php
	
	declare(strict_types=1);
	
	namespace App\Shared\Domain\ValueObject;
	
	use App\Shared\Infrastructure\ValueObject\ValueObject;
	
	abstract class StringValueObject extends ValueObject
	{
		protected string $value;
		
		public function __construct(string $value)
		{
			$this->value = $value;
		}
		
		public function value(): string
		{
			return $this->value;
		}
	}
