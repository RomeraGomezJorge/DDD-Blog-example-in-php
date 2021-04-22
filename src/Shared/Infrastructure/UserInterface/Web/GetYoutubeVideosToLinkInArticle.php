<?php
	
	namespace App\Shared\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentType;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Request;
	
	final class GetYoutubeVideosToLinkInArticle extends WebController
	{
		const NOT_VIDEOS_FOUND_IN_REQUEST = null;
		
		public function __invoke(Request $request)
		{
			$videos = $request->get('youtube_video');
			
			if ($videos === self::NOT_VIDEOS_FOUND_IN_REQUEST) {
				return [];
			}
			
			foreach (array_keys($videos) as $key) {
				if (!$videos[$key]['url']) {
					continue;
				}
				
				$videos[$key]['type'] = AttachmentType::YOUTUBE_VIDEO;
			}
			
			return $this->arrayToObject($videos);
		}
		
		private function arrayToObject(array $arrayToConvent)
		{
			$arrayFormat = json_encode($arrayToConvent);
			
			return json_decode($arrayFormat);
		}
	}