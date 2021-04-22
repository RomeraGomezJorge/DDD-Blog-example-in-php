<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Infrastructure\UserInterface\Web;
	
	use App\Shared\Infrastructure\Symfony\FlashSession;
	
	final class AttachmentInSession
	{
		const NOT_ATTACHMENT_FOUND_IN_SESSION = [];
		const EMPTY_ATTACHMENT = ['file' => '', 'title' => ''];
		const PREFIX_TO_SESSION_VALUES = 'inputs.attachment.0.';
		private FlashSession $flashSession;
		
		public function __construct(FlashSession $flashSession)
		{
			$this->flashSession = $flashSession;
		}
		
		public function __invoke()
		{
			$attachmentFoundInFlashSession = [];
			
			for ($attachmentCounter = 0; $attachmentCounter < 20; $attachmentCounter++) {
				if (!$this->isAttachmentExistsInSession($attachmentCounter)) {
					break;
				}
				
				$attachmentFoundInFlashSession[$attachmentCounter]['file'] = $this->getAttachmentValuedFromFlashSession(
					$attachmentCounter,
					'file');
				
				$attachmentFoundInFlashSession[$attachmentCounter]['title'] = $this->getAttachmentValuedFromFlashSession(
					$attachmentCounter,
					'title');
			}
			
			return ($attachmentFoundInFlashSession === self::NOT_ATTACHMENT_FOUND_IN_SESSION)
				? [self::EMPTY_ATTACHMENT]
				: $attachmentFoundInFlashSession;
		}
		
		private function isAttachmentExistsInSession($passengerCounter): bool
		{
			return !empty($this->flashSession->get(self::PREFIX_TO_SESSION_VALUES . $passengerCounter . '.file'));
		}
		
		private function getAttachmentValuedFromFlashSession(int $fileCounter, string $fieldName)
		{
			$attachmentFieldFromFlashSession = $this->flashSession->get(self::PREFIX_TO_SESSION_VALUES . $fileCounter . '.' . $fieldName);
			
			return (empty($attachmentFieldFromFlashSession))
				? ''
				: $attachmentFieldFromFlashSession;
		}
	}