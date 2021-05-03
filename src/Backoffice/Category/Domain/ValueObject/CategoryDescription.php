<?php
	
	
	namespace App\Backoffice\Category\Domain\ValueObject;
	
	use App\Shared\Infrastructure\ValueObject\StringValueObject;
	use Webmozart\Assert\Assert;
	
	final class CategoryDescription extends StringValueObject
	{
		public function __construct(?string $value)
		{
			$value = trim($value);
			Assert::maxLength($value, 255);
			parent::__construct($value);
		}
	}