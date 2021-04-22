<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Domain\ValueObject;
	
	use App\Shared\Infrastructure\ValueObject\StringValueObject;
	use Webmozart\Assert\Assert;
	
	final class AuthorFullname extends StringValueObject
	{
		public function __construct(string $value)
		{
			$value = trim($value);
			Assert::notEmpty($value);
			Assert::minLength($value, 3);
			Assert::maxLength($value, 100);
			parent::__construct($value);
		}
	}