<?php
	
	declare(strict_types=1);

	namespace App\Backoffice\Article\Domain\Attachment\ValueObject;
	
	use App\Shared\Infrastructure\ValueObject\StringValueObject;
	use Webmozart\Assert\Assert;
	
	final class AttachmentTitle extends StringValueObject
	{
		public function __construct(string $value)
		{
			$value = trim($value);
			Assert::maxLength($value, 100);
			parent::__construct($value);
		}
	}