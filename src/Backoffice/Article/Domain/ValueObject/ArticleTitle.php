<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Domain\ValueObject;
	
	use App\Shared\Infrastructure\ValueObject\StringValueObject;
	use Webmozart\Assert\Assert;
	
	final class ArticleTitle extends StringValueObject
	{
		public function __construct(string $value)
		{
			$value = trim($value);
			Assert::notEmpty($value);
			parent::__construct($value);
		}
	}