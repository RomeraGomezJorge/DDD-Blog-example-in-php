<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Infrastructure\UserInterface\Web;
	
	use App\Shared\Infrastructure\Constant\FormConstant;
	use App\Shared\Infrastructure\RamseyUuidGenerator;
	use App\Shared\Infrastructure\Symfony\FlashSession;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Response;
	
	class AuthorAddController extends WebController
	{
		public function __invoke(FlashSession $flashSession, RamseyUuidGenerator $ramseyUuidGenerator): Response
		{
			return $this->render(TwigTemplateConstants::FORM_FILE_PATH, [
				'list_path' => TwigTemplateConstants::LIST_PATH,
				'page_title' => TwigTemplateConstants::SECTION_TITLE,
				'id' => $ramseyUuidGenerator->generate(),
				'fullname_available_path' => TwigTemplateConstants::FULLNAME_AVAILABLE_PATH,
				'fullname' => $flashSession->get('inputs.fullname'),
				'biography' => $flashSession->get('inputs.biography'),
				'form_action_attribute' => TwigTemplateConstants::CREATE_PATH,
				'submit_button_label' => FormConstant::SUBMIT_BUTTON_VALUE_TO_CREATE,
				'action_to_do' => FormConstant::CREATE_LABEL_TEXT,
			]);
		}
	}
