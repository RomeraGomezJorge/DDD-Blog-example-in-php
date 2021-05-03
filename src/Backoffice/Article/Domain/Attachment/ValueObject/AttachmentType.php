<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Domain\Attachment\ValueObject;
	
	use InvalidArgumentException;
	
	class AttachmentType
	{
		const IMAGE = "image";
		const AUDIO = "audio";
		const DOCUMENT = "document";
		const YOUTUBE_VIDEO = "youtube";
		const VALID_TYPES = [self::IMAGE, self::AUDIO, self::DOCUMENT, self::YOUTUBE_VIDEO];
		private $type;
		
		public function __construct($type)
		{
			$this->ensureIsAValidType($type);
			
			$this->type = $type;
		}
		
		private function ensureIsAValidType($type): void
		{
			if (!in_array($type, self::VALID_TYPES)) {
				throw new InvalidArgumentException(sprintf('El valor <%s> no es un tipo de archivo valido.',
					$type));
			}
		}
		
		public function __toString(): string
		{
			return (string)$this->value();
		}
		
		public function value(): string
		{
			return $this->type;
		}
	}