<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Category;
	
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Shared\Infrastructure\Constant\FormConstant;
	use App\Shared\Infrastructure\Symfony\FlashSession;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Response;
	
	class CategoryAddController extends WebController
	{
		public function __invoke(FlashSession $flashSession): Response
		{
			return $this->render(TwigTemplateConstants::FORM_FILE_PATH, [
				'list_path' => TwigTemplateConstants::LIST_PATH,
				'description_available_path' => TwigTemplateConstants::DESCRIPTION_AVAILABLE_PATH,
				'position_available_path' => TwigTemplateConstants::POSITION_AVAILABLE_PATH,
				'page_title' => TwigTemplateConstants::SECTION_TITLE,
				'id' => Uuid::random(),
				'description' => $flashSession->get('inputs.description'),
				'position' => $flashSession->get('inputs.position'),
				'form_action_attribute' => TwigTemplateConstants::CREATE_PATH,
				'submit_button_label' => FormConstant::SUBMIT_BUTTON_VALUE_TO_CREATE,
				'action_to_do' => FormConstant::CREATE_LABEL_TEXT,
			]);
		}
	}
