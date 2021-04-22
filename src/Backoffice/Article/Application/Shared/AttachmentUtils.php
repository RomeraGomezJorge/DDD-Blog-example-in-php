<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Application\Shared;
	
	use App\Backoffice\Article\Domain\Article;
	use App\Backoffice\Article\Domain\Attachment\Attachment;
	use App\Backoffice\Article\Domain\Attachment\AttachmentManager;
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentTitle;
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentType;
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentUrl;
	
	final class AttachmentUtils
	{
		const URL_IS_NOT_DEFINE = '';
		private AttachmentManager $attachmentManager;
		
		public function __construct(AttachmentManager $attachmentManager)
		{
			$this->attachmentManager = $attachmentManager;
		}
		
		public function attachFiles(array $attachments, Article $article): void
		{
			if (empty($attachments)) {
				return;
			}
			
			foreach ($attachments as $attachment) {
				if ($attachment->url === self::URL_IS_NOT_DEFINE) {
					continue;
				}
				
				$article->addAttachment(
					Attachment::create(
						new AttachmentUrl($attachment->url),
						new AttachmentType($attachment->type),
						new AttachmentTitle($attachment->title)
					)
				);
				
				if ($attachment->type != AttachmentType::IMAGE) {
					continue;
				}
				
				$this->attachmentManager->resize($attachment->url);
			}
		}
		
		public function attachVideos(array $videos, Article $article): void
		{
			if (empty($videos)) {
				return;
			}
			
			foreach ($videos as $video) {
				if ($video->url === self::URL_IS_NOT_DEFINE) {
					continue;
				}
				
				$article->addAttachment(
					Attachment::create(
						new AttachmentUrl($video->url),
						new AttachmentType($video->type),
						new AttachmentTitle($video->title)
					)
				);
			}
		}
	}