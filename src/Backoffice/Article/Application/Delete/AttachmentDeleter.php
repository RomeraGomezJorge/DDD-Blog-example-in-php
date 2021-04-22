<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Application\Delete;
	
	use App\Backoffice\Article\Application\Get\Single\ArticleFinder;
	use App\Backoffice\Article\Domain\ArticleRepository;
	use App\Backoffice\Article\Domain\Attachment\Attachment;
	use App\Backoffice\Article\Domain\Attachment\AttachmentManager;
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentTitle;
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentType;
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentUrl;
	
	final class AttachmentDeleter
	{
		const RETURN_ASSOCIATIVE_ARRAY = true;
		private ArticleRepository $repository;
		private ArticleFinder $finder;
		private AttachmentManager $attachmentManager;
		
		public function __construct(ArticleRepository $repository, AttachmentManager $attachmentManager)
		{
			$this->repository = $repository;
			$this->finder = new ArticleFinder($repository);
			$this->attachmentManager = $attachmentManager;
		}
		
		public function __invoke(string $id, string $attachment)
		{
			$articleFound = $this->finder->__invoke($id);
			
			$attachment = json_decode($attachment, self::RETURN_ASSOCIATIVE_ARRAY);
			
			$attachmentToRemove = Attachment::create(
				new AttachmentUrl($attachment['url']),
				new AttachmentType($attachment['type']),
				new AttachmentTitle($attachment['title']));
			
			$articleFound->removeAttachment($attachmentToRemove);
			
			$this->repository->save($articleFound);
			
			$this->attachmentManager->delete($attachmentToRemove->url());
		}
	}