<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Subcategory;
	
	use App\Backoffice\Category\Infrastructure\UserInterface\Web\Category\TwigTemplateConstants as CategoryTwigTemplateConstants;
	use App\Shared\Infrastructure\Constant\FormConstant;
	use App\Shared\Infrastructure\RamseyUuidGenerator;
	use App\Shared\Infrastructure\RelatedEntities;
	use App\Shared\Infrastructure\Symfony\FlashSession;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Response;
	
	class SubcategoryAddController extends WebController
	{
		public function __invoke(
			FlashSession $flashSession,
			RamseyUuidGenerator $ramseyUuidGenerator,
			RelatedEntities $relatedEntities
		): Response {
			return $this->render(TwigTemplateConstants::FORM_FILE_PATH, [
				'list_path' => TwigTemplateConstants::LIST_PATH,
				'description_available_path' => TwigTemplateConstants::DESCRIPTION_AVAILABLE_PATH,
				'position_available_path' => TwigTemplateConstants::POSITION_AVAILABLE_PATH,
				'add_category_by_modal_path' => CategoryTwigTemplateConstants::ADD_CATEGORY_BY_MODAL_PATH,
				'page_title' => TwigTemplateConstants::SECTION_TITLE,
				'id' => $ramseyUuidGenerator->generate(),
				'description' => $flashSession->get('inputs.description'),
				'position' => $flashSession->get('inputs.position'),
				'parent_id' => $flashSession->get('inputs.parent_id'),
				'parent_categories' => $relatedEntities->getAllParentCategoriesSortedAlphabetically(),
				'form_action_attribute' => TwigTemplateConstants::CREATE_PATH,
				'submit_button_label' => FormConstant::SUBMIT_BUTTON_VALUE_TO_CREATE,
				'action_to_do' => FormConstant::CREATE_LABEL_TEXT,
			]);
		}
	}
