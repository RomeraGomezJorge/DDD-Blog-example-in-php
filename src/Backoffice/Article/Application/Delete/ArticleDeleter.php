<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Application\Delete;
	
	use App\Backoffice\Article\Application\Get\Single\ArticleFinder;
	use App\Backoffice\Article\Domain\ArticleRepository;
	use App\Backoffice\Article\Domain\Attachment\AttachmentManager;
	
	final class ArticleDeleter
	{
		private ArticleRepository $repository;
		private ArticleFinder $finder;
		private AttachmentManager $attachmentManager;
		
		public function __construct(ArticleRepository $repository, AttachmentManager $attachmentManager)
		{
			$this->repository = $repository;
			$this->finder = new ArticleFinder($repository);
			$this->attachmentManager = $attachmentManager;
		}
		
		public function __invoke(string $id)
		{
			$article = $this->finder->__invoke($id);
			
			$attachmentFilesToDelete = $article->getAttachments();
			
			$this->repository->delete($article);
			
			foreach ($attachmentFilesToDelete as $file) {
				$this->attachmentManager->delete($file['url']);
			}
		}
	}