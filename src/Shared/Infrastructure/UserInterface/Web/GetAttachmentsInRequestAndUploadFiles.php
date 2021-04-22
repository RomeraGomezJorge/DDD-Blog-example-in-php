<?php
	
	namespace App\Shared\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentType;
	use App\Shared\Infrastructure\Symfony\WebController;
	use InvalidArgumentException;
	use Symfony\Component\HttpFoundation\File\Exception\FileException;
	use Symfony\Component\HttpFoundation\File\UploadedFile;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\String\Slugger\SluggerInterface;
	
	final class GetAttachmentsInRequestAndUploadFiles extends WebController
	{
		const ALLOWED_IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png'];
		const ALLOWED_AUDIO_EXTENSIONS = ['mp3'];
		const ALLOWED_DOCUMENT_EXTENSIONS = [
			'dot',
			'wbk',
			'docx',
			'docm',
			'dotx',
			'dotm',
			'docb',
			'xls',
			'xlt',
			'xlm',
			'xlsx',
			'xlsm',
			'xltx',
			'xltm',
			'xlsb',
			'xla',
			'xlam',
			'xll',
			'xlw',
			'ppt',
			'pot',
			'pps',
			'pptx',
			'pptm',
			'potx',
			'potm',
			'ppam',
			'ppsx',
			'ppsm',
			'sldx',
			'sldm',
			'pdf',
			'txt'
		];
		private SluggerInterface $slugger;
		
		public function __construct(SluggerInterface $slugger)
		{
			$this->slugger = $slugger;
		}
		
		public function __invoke(Request $request)
		{
			$attachmentsTitles = $request->get('attachment');
			
			$attachment = [];
			
			$files = $request->files->get('attachment');
			
			foreach (array_keys($files) as $key) {
				if (!$files[$key]['file']) {
					continue;
				}
				
				$extension = $files[$key]['file']->guessExtension();
				
				$attachment[$key]['url'] = $this->uploadFileAndGetFileLink($files[$key]['file'], $request);
				$attachment[$key]['title'] = $attachmentsTitles[$key]['title'];
				$attachment[$key]['type'] = $this->getFileType($extension);
			}
			
			return $this->arrayToObject($attachment);
		}
		
		private function uploadFileAndGetFileLink(UploadedFile $fileToUpload, Request $request): string
		{
			$articleTitle = $request->get('title');
			
			$fileName = $this->slugger->slug($articleTitle . '-' . uniqid()) . '.' . $fileToUpload->guessExtension();
			
			try {
				$fileToUpload->move($this->getParameter('article_attachment_directory'), $fileName);
			} catch (FileException $e) {
				$e->getMessage();
			}
			
			return $fileName;
		}
		
		private function getFileType(string $extension): string
		{
			switch ($extension) {
				case in_array($extension, self::ALLOWED_IMAGE_EXTENSIONS):
					return AttachmentType::IMAGE;
				case in_array($extension, self::ALLOWED_AUDIO_EXTENSIONS):
					return AttachmentType::AUDIO;
				case in_array($extension, self::ALLOWED_DOCUMENT_EXTENSIONS):
					return AttachmentType::DOCUMENT;
				default:
					throw new InvalidArgumentException('Este tipo ' . $extension . ' de archivo no está permitido por razones de seguridad');
			}
		}
		
		private function arrayToObject(array $arrayToConvent)
		{
			$arrayFormat = json_encode($arrayToConvent);
			
			return json_decode($arrayFormat);
		}
	}