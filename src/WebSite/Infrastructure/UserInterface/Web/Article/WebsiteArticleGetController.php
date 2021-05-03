<?php
	
	declare(strict_types=1);
	
	namespace App\WebSite\Infrastructure\UserInterface\Web\Article;
	
	use App\Backoffice\Article\Infrastructure\Storage\LocalAttachmentManager;
	use App\Shared\Infrastructure\Symfony\WebController;
	use App\WebSite\Application\Get\Single\ArticleFinder;
	use App\WebSite\Application\Get\Single\NextArticleFinder;
	use App\WebSite\Application\Get\Single\PreviousArticleFinder;
	use App\WebSite\Infrastructure\UserInterface\Web\TwigTemplateConstants;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class WebsiteArticleGetController extends WebController
	{
		public function __invoke(
			Request $request,
			ArticleFinder $finder,
			NextArticleFinder $nextArticleFinder,
			PreviousArticleFinder $previousArticleFinder
		): Response {
			$slug = $request->get('slug');
			
			$articleFound = $finder->__invoke($slug);
			
			return $this->render(TwigTemplateConstants::ARTICLE_MAIN_BASE_FILE_PATH,
				[
					'article' => $articleFound,
					'next_article' => $nextArticleFinder->__invoke($articleFound),
					'previous_article' => $previousArticleFinder->__invoke($articleFound),
					'attachment_folder' => $this->getParameter('article_attachment_directory'),
					'large_image_extension' => LocalAttachmentManager::LARGE_IMAGE['extension'],
				]
			);
		}
	}
	

	