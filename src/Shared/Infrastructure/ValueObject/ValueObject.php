<?php
	
	declare(strict_types=1);
	
	namespace App\Shared\Infrastructure\ValueObject;
	
	abstract class ValueObject
	{
		public function __toString(): string
		{
			return (string)$this->value();
		}
		
		abstract public function value();
		
		public function isNotEqual($other): bool
		{
			return !$this->isEqual($other);
		}
		
		public function isEqual($other): bool
		{
			return $this->value() === $other;
		}
		
		public function isNotNull(): bool
		{
			return !$this->isNull();
		}
		
		public function isNull(): bool
		{
			return $this->value() === null;
		}
		
		public function isNotEmpty(): bool
		{
			return !$this->isEmpty();
		}
		
		public function isEmpty(): bool
		{
			return empty($this->value());
		}
	}