<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Domain\ValueObject;
	
	use InvalidArgumentException;
	
	final class UserPassword
	{
		const MINIMUM_LENGTH = 8;
		const MAXIMUM_LENGTH = 20;
		private $password;
		
		public function __construct(string $aPassword)
		{
			$aPassword = trim($aPassword);
			
			$this->ensureLengthIsBetween8To20Characters($aPassword);
			
			$this->ensureHasAtLeastOneUppercaseCharacter($aPassword);
			
			$this->ensureHasAtLeastOneNumber($aPassword);
			
			$this->password = $aPassword;
		}
		
		private function ensureLengthIsBetween8To20Characters(string $aPassword): void
		{
			$length = $this->stringLength($aPassword);
			
			if ($length < self::MINIMUM_LENGTH) {
				throw new InvalidArgumentException(sprintf('%s debe tener entre %s y %s  caracteres.',
					__METHOD__,
					self::MINIMUM_LENGTH,
					self::MAXIMUM_LENGTH));
			}
			
			if ($length > self::MAXIMUM_LENGTH) {
				throw new InvalidArgumentException(sprintf('%s debe tener entre %s y %s  caracteres.',
					__METHOD__,
					self::MINIMUM_LENGTH,
					self::MAXIMUM_LENGTH));
			}
		}
		
		private function stringLength($string)
		{
			$encoding = mb_detect_encoding($string);
			
			return mb_strlen($string, $encoding);
		}
		
		private function ensureHasAtLeastOneUppercaseCharacter(string $password): void
		{
			if (1 !== preg_match('/[A-Z]+/', $password)) {
				throw new InvalidArgumentException('La contraseña debe incluir como minimo una letra en mayúscula.');
			};
		}
		
		private function ensureHasAtLeastOneNumber(string $password): void
		{
			if (1 !== preg_match('/[0-9Z]+/', $password)) {
				throw new InvalidArgumentException('La contraseña debe incluir como minimo un número.');
			};
		}
		
		public function value(): string
		{
			return $this->password;
		}
	}