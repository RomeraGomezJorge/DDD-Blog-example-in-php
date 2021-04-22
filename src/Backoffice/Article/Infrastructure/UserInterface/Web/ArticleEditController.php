<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\Article\Application\Get\Single\ArticleFinder;
	use App\Backoffice\Article\Domain\Attachment\ValueObject\AttachmentType;
	use App\Backoffice\Author\Infrastructure\UserInterface\Web\TwigTemplateConstants as AuthorTwigTemplateConstants;
	use App\Backoffice\Category\Infrastructure\UserInterface\Web\Category\TwigTemplateConstants as CategoryTwigTemplateConstants;
	use App\Shared\Infrastructure\Constant\FormConstant;
	use App\Shared\Infrastructure\RelatedEntities;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class ArticleEditController extends WebController
	{
		public function __invoke(
			Request $request,
			ArticleFinder $finder,
			RelatedEntities $relatedEntities,
			AttachmentInSession $attachmentInSession
		): Response {
			$article = $finder->__invoke($request->get('id'));
			
			
			return $this->render(TwigTemplateConstants::FORM_FILE_PATH, [
				'page_title' => TwigTemplateConstants::SECTION_TITLE,
				'list_path' => TwigTemplateConstants::LIST_PATH,
				'id' => $article->id(),
				'entry' => $article->entry(),
				'title' => $article->title(),
				'excerpt' => $article->excerpt(),
				'body' => $article->body(),
				'state' => $article->state(),
				'author_id' => $article->author()->id(),
				'authors' => $relatedEntities->getAllAuthorsSortedAlphabetically(),
				'category_id' => $article->category()->id(),
				'categories' => $relatedEntities->getAllParentCategoriesSortedAlphabetically(),
				'attachment_in_session' => $attachmentInSession->__invoke(),
				'attachments' => $article->getAttachments(),
				'allowed_files' => TwigTemplateConstants::ALLOWED_FILES,
				'attachment_delete_modal_confirmation_path' => TwigTemplateConstants::ATTACHMENT_DELETE_MODAL_CONFIRMATION_PATH,
				'attachment_delete_path' => TwigTemplateConstants::ATTACHMENT_DELETE_PATH,
				'attachment_type_descriptions' => [
					AttachmentType::IMAGE => 'Imagen',
					AttachmentType::AUDIO => 'Audio',
					AttachmentType::DOCUMENT => 'Documento',
					AttachmentType::YOUTUBE_VIDEO => 'Youtube'
				],
				'attachment_file_directory' => $this->getParameter('article_attachment_directory'),
				'title_available_path' => TwigTemplateConstants::TITLE_AVAILABLE_PATH,
				'form_action_attribute' => TwigTemplateConstants::UPDATE_PATH,
				'add_category_by_modal_path' => CategoryTwigTemplateConstants::ADD_CATEGORY_BY_MODAL_PATH,
				'add_author_by_modal_path' => AuthorTwigTemplateConstants::ADD_AUTHOR_BY_MODAL_PATH,
				'submit_button_label' => FormConstant::SUBMIT_BUTTON_VALUE_TO_UPDATE,
				'action_to_do' => FormConstant::UPDATE_LABEL_TEXT
			]);
		}
	}
