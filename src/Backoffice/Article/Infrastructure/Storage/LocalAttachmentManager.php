<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Infrastructure\Storage;
	
	use App\Backoffice\Article\Domain\Attachment\AttachmentManager;
	use Exception;
	use Gumlet\ImageResize;
	use Symfony\Component\DependencyInjection\ContainerInterface;
	use Symfony\Component\Filesystem\Filesystem;
	
	final class LocalAttachmentManager implements AttachmentManager
	{
		const TINY_IMAGE = ["with" => "100", "height" => "100", "extension" => "_100x100.jpg"];
		const SMALL_IMAGE = ["with" => "200", "height" => "200", "extension" => "_200x200.jpg"];
		const MEDIUM_IMAGE = ["with" => "300", "height" => "300", "extension" => "_300x300.jpg"];
		const LARGE_SIZE_IMAGE = "";
		const HUGE_SIZE_IMAGE = "";
		private string $articleAttachmentDirectory;
		private Filesystem $filesystem;
		
		public function __construct(Filesystem $filesystem, ContainerInterface $container)
		{
			$this->filesystem = $filesystem;
			$this->articleAttachmentDirectory = $container->getParameter('article_attachment_directory');
		}
		
		public function delete(string $attachmentFileName): void
		{
			try {
				$this->filesystem->remove($this->articleAttachmentDirectory . '/' . $attachmentFileName);
				$this->filesystem->remove($this->articleAttachmentDirectory . '/' . $attachmentFileName . self::TINY_IMAGE['extension']);
				$this->filesystem->remove($this->articleAttachmentDirectory . '/' . $attachmentFileName . self::SMALL_IMAGE['extension']);
				$this->filesystem->remove($this->articleAttachmentDirectory . '/' . $attachmentFileName . self::MEDIUM_IMAGE['extension']);
			} catch (Exception $e) {
				$e->getMessage();
			}
		}
		
		public function resize(string $imageFileName): void
		{
			$this->generateImage($imageFileName, self::TINY_IMAGE);
			$this->generateImage($imageFileName, self::SMALL_IMAGE);
			$this->generateImage($imageFileName, self::MEDIUM_IMAGE);
		}
		
		private function generateImage(string $imageFileName, array $size): void
		{
			$image = new ImageResize($this->articleAttachmentDirectory . $imageFileName);
			$image->resizeToBestFit($size['with'], $size['height']);
			$image->save($this->articleAttachmentDirectory . $imageFileName . $size['extension']);
		}
	}