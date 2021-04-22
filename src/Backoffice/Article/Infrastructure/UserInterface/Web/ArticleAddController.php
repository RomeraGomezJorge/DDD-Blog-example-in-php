<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\Author\Infrastructure\UserInterface\Web\TwigTemplateConstants as AuthorTwigTemplateConstants;
	use App\Backoffice\Category\Infrastructure\UserInterface\Web\Category\TwigTemplateConstants as CategoryTwigTemplateConstants;
	use App\Shared\Infrastructure\Constant\FormConstant;
	use App\Shared\Infrastructure\RamseyUuidGenerator;
	use App\Shared\Infrastructure\RelatedEntities;
	use App\Shared\Infrastructure\Symfony\FlashSession;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Response;
	
	class ArticleAddController extends WebController
	{
		public function __invoke(
			FlashSession $flashSession,
			RamseyUuidGenerator $ramseyUuidGenerator,
			RelatedEntities $relatedEntities,
			AttachmentInSession $articleMediaFileInSession
		): Response {
			return $this->render(TwigTemplateConstants::FORM_FILE_PATH, [
				'list_path' => TwigTemplateConstants::LIST_PATH,
				'page_title' => TwigTemplateConstants::SECTION_TITLE,
				'id' => $ramseyUuidGenerator->generate(),
				'entry' => $flashSession->get('inputs.entry'),
				'title' => $flashSession->get('inputs.title'),
				'excerpt' => $flashSession->get('inputs.excerpt'),
				'body' => $flashSession->get('inputs.body'),
				'state' => $flashSession->get('inputs.state'),
				'author_id' => $flashSession->get('inputs.author_id'),
				'category_id' => $flashSession->get('inputs.category_id'),
				'authors' => $relatedEntities->getAllAuthorsSortedAlphabetically(),
				'categories' => $relatedEntities->getAllParentCategoriesSortedAlphabetically(),
				'attachment_in_session' => $articleMediaFileInSession->__invoke(),
				'allowed_files' => TwigTemplateConstants::ALLOWED_FILES,
				'title_available_path' => TwigTemplateConstants::TITLE_AVAILABLE_PATH,
				'form_action_attribute' => TwigTemplateConstants::CREATE_PATH,
				'add_category_by_modal_path' => CategoryTwigTemplateConstants::ADD_CATEGORY_BY_MODAL_PATH,
				'add_author_by_modal_path' => AuthorTwigTemplateConstants::ADD_AUTHOR_BY_MODAL_PATH,
				'submit_button_label' => FormConstant::SUBMIT_BUTTON_VALUE_TO_CREATE,
				'action_to_do' => FormConstant::CREATE_LABEL_TEXT,
			]);
		}
	}
