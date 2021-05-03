<?php
	
	declare(strict_types=1);
	
	namespace App\Shared\Infrastructure\Symfony;
	
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentType;
	use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
	
	final class Attachment
	{
		private ParameterBagInterface $parameterBag;
		
		public function __construct(ParameterBagInterface $parameterBag)
		{
			$this->parameterBag = $parameterBag;
		}
		
		public function hasOnlyOneImage($attachments): bool
		{
			return $this->hasAtLeastOneItems($attachments, AttachmentType::IMAGE);
		}
		
		public function hasAtLeastOneImage($attachments): bool
		{
			return $this->hasAtLeastOneItems($attachments, AttachmentType::IMAGE);
		}
		
		public function hasOnlyOneYoutubeVideo($attachments): bool
		{
			return $this->hasAtLeastOneItems($attachments, AttachmentType::YOUTUBE_VIDEO);
		}
		
		public function hasAtLeastOneDocument($attachments): bool
		{
			return $this->hasAtLeastOneItems($attachments, AttachmentType::DOCUMENT);
		}
		
		public function hasAtLeastOneAudio($attachments): bool
		{
			return $this->hasAtLeastOneItems($attachments, AttachmentType::AUDIO);
		}
		
		private function hasAtLeastOneItems($attachments, string $attachmentType): bool
		{
			if (is_array($attachments)) {
				$attachments = json_encode($attachments);
			}
			
			$numberImagesFound = substr_count($attachments, $attachmentType);
			
			return $numberImagesFound > 0;
		}
		
		public function getFirstImage($images): string
		{
			$attachments = (is_array($images)) ? $images : $this->toArray($images);
			
			$key = array_search(AttachmentType::IMAGE, $attachments);
			
			return $this->parameterBag->get('article_attachment_directory') . $attachments[$key]['url'];
		}
		
		public function getFirstYoutubeVideo($images): string
		{
			$attachments = (is_array($images)) ? $images : $this->toArray($images);
			
			$key = array_search(AttachmentType::YOUTUBE_VIDEO, $attachments);
			
			return $attachments[$key]['url'];
		}
		
		public function getAllImagesAndVideos($images): array
		{
			$attachments = (is_array($images)) ? $images : $this->toArray($images);
			
			return array_filter($attachments, function ($arrayValue) {
				return $arrayValue['type'] == AttachmentType::IMAGE || $arrayValue['type'] == AttachmentType::YOUTUBE_VIDEO;
			});
		}
		
		public function getAllDocuments($attachments): array
		{
			$attachmentsArray = (is_array($attachments)) ? $attachments : $this->toArray($attachments);
			
			return array_filter($attachmentsArray, function ($arrayValue) {
				return $arrayValue['type'] == AttachmentType::DOCUMENT;
			});
		}
		
		public function getAllAudios($attachments): array
		{
			$attachmentsArray = (is_array($attachments)) ? $attachments : $this->toArray($attachments);
			
			return array_filter($attachmentsArray, function ($arrayValue) {
				return $arrayValue['type'] == AttachmentType::AUDIO;
			});
		}
		
		public function hasMoreThanOneImageOrVideo($images): bool
		{
			$imageString = json_encode($images);
			
			$numberImagesFound = substr_count($imageString, AttachmentType::IMAGE);
			
			$numberVideosFound = substr_count($imageString, AttachmentType::YOUTUBE_VIDEO);
			
			return ($numberImagesFound + $numberVideosFound) > 1;
		}
		
		public function isAVideo(array $attachment): bool
		{
			return array_search(AttachmentType::YOUTUBE_VIDEO, $attachment);
		}
		
		public function isAnImage(array $attachment): bool
		{
			return array_search(AttachmentType::IMAGE, $attachment);
		}
		
		private function toArray(string $json): array
		{
			return json_decode($json, true);
		}
	}
