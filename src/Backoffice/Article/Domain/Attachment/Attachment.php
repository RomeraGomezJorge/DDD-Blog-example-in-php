<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Domain\Attachment;
	
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentTitle;
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentType;
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentUrl;
	
	class Attachment
	{
		private string $url;
		private string $type;
		private string $title;
		
		public static function create(
			AttachmentUrl $url,
			AttachmentType $type,
			?AttachmentTitle $title
		): self {
			$attachment = new self();
			$attachment->url = $url->value();
			$attachment->type = $type->value();
			$attachment->title = $title->value();
			
			return $attachment;
		}
		
		public function toArray(): array
		{
			return [
				'url' => $this->url,
				'type' => $this->type,
				'title' => $this->title,
			];
		}
		
		public function url(): string
		{
			return $this->url;
		}
	}
