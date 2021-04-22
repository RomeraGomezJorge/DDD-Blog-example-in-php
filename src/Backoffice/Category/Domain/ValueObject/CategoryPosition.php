<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Domain\ValueObject;
	
	use App\Shared\Infrastructure\ValueObject\IntValueObject;
	use Webmozart\Assert\Assert;
	
	final class CategoryPosition extends IntValueObject
	{
		public function __construct(int $value)
		{
			Assert::greaterThan($value, 0);
			parent::__construct($value);
		}
	}