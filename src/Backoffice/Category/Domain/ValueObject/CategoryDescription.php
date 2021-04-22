<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Domain\ValueObject;
	
	use App\Shared\Infrastructure\ValueObject\StringValueObject;
	use Webmozart\Assert\Assert;
	
	final class CategoryDescription extends StringValueObject
	{
		public function __construct(string $value)
		{
			$value = trim($value);
			Assert::notEmpty($value);
			Assert::minLength($value, 3);
			Assert::maxLength($value, 255);
			parent::__construct($value);
		}
	}