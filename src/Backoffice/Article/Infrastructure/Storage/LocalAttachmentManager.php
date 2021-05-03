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
		const TINY_IMAGE = ["width" => "100", "height" => "100", "extension" => "_100x100.jpg"];
		const SMALL_IMAGE = ["width" => "242", "height" => "161", "extension" => "_242x161.jpg"];
		const MEDIUM_IMAGE = ["width" => "366", "height" => "271", "extension" => "_366x271.jpg"];
		const LARGE_IMAGE =  ["width" => "720", "height" => "478", "extension" => "_720x478.jpg"];
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
				$this->filesystem->remove($this->articleAttachmentDirectory . '/' . $attachmentFileName . self::LARGE_IMAGE['extension']);
			} catch (Exception $e) {
				$e->getMessage();
			}
		}
		
		public function resize(string $imageFileName): void
		{
			$this->generateImage($imageFileName, self::TINY_IMAGE);
			$this->generateImage($imageFileName, self::SMALL_IMAGE);
			$this->generateImage($imageFileName, self::MEDIUM_IMAGE);
			$this->generateImage($imageFileName, self::LARGE_IMAGE);
		}
		
		private function generateImage(string $imageFileName, array $size): void
		{
			$image = new ImageResize($this->articleAttachmentDirectory . $imageFileName);
			$image->resize($size['width'], $size['height'], $allow_enlarge = True);
			$image->save($this->articleAttachmentDirectory . $imageFileName . $size['extension']);
		}
	}