<?php
	
	declare(strict_types=1);
	
	namespace App\Shared\Domain\ValueObject;
	
	use InvalidArgumentException;
	use Ramsey\Uuid\Uuid as RamseyUuid;
	
	class Uuid
	{
		protected string $value;
		
		public function __construct(string $value)
		{
			$this->ensureIsValidUuid($value);
			
			$this->value = $value;
		}
		
		private function ensureIsValidUuid($id): void
		{
			if (!RamseyUuid::isValid($id)) {
				throw new InvalidArgumentException(sprintf('El valor <%s> no es Uuid valido.', $id));
			}
		}
		
		public static function random(): self
		{
			return new static(RamseyUuid::uuid4()->toString());
		}
		
		public function equals(Uuid $other): bool
		{
			return $this->value() === $other->value();
		}
		
		public function value(): string
		{
			return $this->value;
		}
		
		public function __toString(): string
		{
			return $this->value();
		}
	}
