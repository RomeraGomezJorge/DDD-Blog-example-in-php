<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Infrastructure\UserInterface\Web;
	
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use App\Backoffice\Article\Application\Put\ArticleChangeDetails;
	use App\Shared\Infrastructure\Constant\MessageConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use App\Shared\Infrastructure\UserInterface\Web\GetAttachmentsInRequestAndUploadFiles;
	use App\Shared\Infrastructure\UserInterface\Web\GetYoutubeVideosToLinkInArticle;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class ArticlePutController extends WebController
	{
		private GetAttachmentsInRequestAndUploadFiles $getAttachmentsOnRequestAndUploadFiles;
		private GetYoutubeVideosToLinkInArticle $getYoutubeVideosToLinkInArticle;
		
		public function __construct(
			GetAttachmentsInRequestAndUploadFiles $getArticleMediaFilesOnRequestAndUploadFiles,
			GetYoutubeVideosToLinkInArticle $getYoutubeVideosToLinkInArticle
		) {
			$this->getAttachmentsOnRequestAndUploadFiles = $getArticleMediaFilesOnRequestAndUploadFiles;
			$this->getYoutubeVideosToLinkInArticle = $getYoutubeVideosToLinkInArticle;
		}
		
		public function __invoke(Request $request, ArticleChangeDetails $updater): Response
		{
			$isCsrfTokenValid = $this->isCsrfTokenValid($request->get('id'), $request->get('csrf_token'));
			
			if (!$isCsrfTokenValid) {
				return $this->redirectWithMessage('error_page', MessageConstant::INVALID_TOKEN_CSFR_MESSAGE);
			}
			
			$validationErrors = ValidationRulesToCreateAndUpdate::verify($request);
			
			return $validationErrors->count() !== 0
				? $this->redirectWithErrors(TwigTemplateConstants::EDIT_PATH, $validationErrors, $request)
				: $this->update($request, $updater);
		}
		
		private function update(Request $request, ArticleChangeDetails $updater): RedirectResponse
		{
			$updater->__invoke(
				$request->get('id'),
				$request->get('entry'),
				$request->get('title'),
				$request->get('excerpt'),
				$request->get('body'),
				$request->get('state'),
				$request->get('author_id'),
				$request->get('category_id'),
				$this->getAttachmentsOnRequestAndUploadFiles->__invoke($request),
				$this->getYoutubeVideosToLinkInArticle->__invoke($request)
			);
			
			return $this->redirectWithMessage(
				TwigTemplateConstants::LIST_PATH,
				MessageConstant::SUCCESS_MESSAGE_TO_UPDATE
			);
		}
	}
