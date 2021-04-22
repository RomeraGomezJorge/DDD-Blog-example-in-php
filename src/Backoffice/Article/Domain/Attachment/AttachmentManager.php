<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Domain\Attachment;
	
	interface AttachmentManager
	{
		public function delete(string $attachmentFileName): void;
		
		public function resize(string $attachmentFileName): void;
	}