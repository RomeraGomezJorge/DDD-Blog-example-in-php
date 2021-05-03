<?php
	
	declare(strict_types=1);
	
	namespace App\Shared\Infrastructure\Symfony;
	
	use App\Backoffice\Article\Domain\Article;
	use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
	final class SocialShareURLs
	{
		const FACEBOOK_SHARE_LINK = 'https://www.facebook.com/sharer/sharer.php?u=';
		const TWITTER_SHARE_LINK = 'https://twitter.com/intent/tweet?url=';
		private UrlGeneratorInterface $urlGenerator;
		/**
		 * @var Attachment
		 */
		private $attachment;
		
		public function __construct( UrlGeneratorInterface $urlGenerator,Attachment $attachment)
		{
			$this->urlGenerator = $urlGenerator;
			$this->attachment = $attachment;
		}
		
		public function  createFacebook(String $slug):string
		{
			 return self::FACEBOOK_SHARE_LINK.$this->generateArticleLink($slug);
		}
		
		public function printFacebookMetaTags(Article $article):void
		{
			$metaTags='<meta name="title" content="'.$article->title().'">';
	        $metaTags.='<meta name="description" content="'.$article->excerpt().'">';
	        $metaTags.='<meta property="og:type" content="website">';
	        $metaTags.='<meta property="og:url" content="'.$this->generateArticleLink($article->slug()).'">';
	        $metaTags.='<meta property="og:title" content="'.$article->title().'">';
	        $metaTags.='<meta property="og:description" content="'.$article->excerpt().'">';
	        $metaTags.='<meta property="og:image" content="'.$this->attachment->getFirstImage($article->attachements()).'">';
	        $metaTags.='<meta property="og:image:type" content="image/jpeg">';
	        $metaTags.='<meta property="og:image:width" content="660">';
	        $metaTags.='<meta property="og:image:height" content="400">';
			echo $metaTags;
			
		}
		
		public function  createTwitter(String $slug):string
		{
			 return self::TWITTER_SHARE_LINK . $this->generateArticleLink($slug);
		}
		
		public function printTwitterMetaTags( Article $article):void
		{
	        $metaTags ='<meta name="twitter:card" content="photo">';
	        $metaTags .='<meta name="twitter:title" content=" '.$article->title().'">';
	        $metaTags .='<meta property="twitter:image" content="'.$this->attachment->getFirstImage($article->attachements()).'">';
	        $metaTags .='<meta property="twitter:image:width" content="660">';
	        $metaTags .='<meta property="twitter:image:height" content="400">';
	        $metaTags .='<meta name="twitter:url" content="'.$this->generateArticleLink($article->slug()).'">';
			
			echo $metaTags;
		}
		
		private function generateArticleLink(string $slug):string
		{
			return $this->urlGenerator->generate('article',['slug' => $slug]);
		}
	}