<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Domain\ValueObject;
	
	use App\Shared\Infrastructure\ValueObject\StringValueObject;
	use Webmozart\Assert\Assert;
	
	final class AuthorBiography extends StringValueObject
	{
		public function __construct(string $value)
		{
			$value = trim($value);
			Assert::maxLength($value, 255);
			parent::__construct($value);
		}
	}