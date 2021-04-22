<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Domain\ValueObject;
	
	use App\Shared\Domain\ValueObject\IntValueObject;
	use Webmozart\Assert\Assert;
	
	final class UserStatus extends IntValueObject
	{
		const ENABLE = 1;
		consT DISABLE = 0;
		
		public function __construct(int $value)
		{
			Assert::range($value, self::DISABLE, self::ENABLE);
			parent::__construct($value);
		}
	}