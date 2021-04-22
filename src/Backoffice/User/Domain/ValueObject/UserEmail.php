<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Domain\ValueObject;
	
	use App\Backoffice\User\Domain\Exception\UserEmailInvalid;
	use App\Shared\Domain\ValueObject\StringValueObject;
	use InvalidArgumentException;
	
	final class UserEmail extends StringValueObject
	{
		private string $email;
		private string $domain;
		private string $localPart;
		
		public function __construct(string $value)
		{
			$value = trim($value);
			if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
				throw new InvalidArgumentException(sprintf('El el correo electronico "%s" que ha ingresado no es válido.',
					$value));
			}
			$this->email = $value;
			$this->localPart = implode(explode('@', $this->email, -1), '@');
			$this->domain = str_replace($this->localPart . '@', '', $this->email);
			parent::__construct($value);
		}
		
		public function localPart(): string
		{
			return $this->localPart;
		}
		
		public function domain(): string
		{
			return $this->domain;
		}
	}
