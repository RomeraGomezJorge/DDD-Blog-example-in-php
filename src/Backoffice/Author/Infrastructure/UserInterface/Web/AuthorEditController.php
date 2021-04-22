<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\Author\Application\Get\Single\AuthorFinder;
	use App\Shared\Infrastructure\Constant\FormConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class AuthorEditController extends WebController
	{
		public function __invoke(Request $request, AuthorFinder $finder): Response
		{
			$Author = $finder->__invoke($request->get('id'));
			
			return $this->render(TwigTemplateConstants::FORM_FILE_PATH, [
				'page_title' => TwigTemplateConstants::SECTION_TITLE,
				'list_path' => TwigTemplateConstants::LIST_PATH,
				'id' => $Author->id(),
				'fullname' => $Author->fullname(),
				'biography' => $Author->biography(),
				'fullname_available_path' => TwigTemplateConstants::FULLNAME_AVAILABLE_PATH,
				'form_action_attribute' => TwigTemplateConstants::UPDATE_PATH,
				'submit_button_label' => FormConstant::SUBMIT_BUTTON_VALUE_TO_UPDATE,
				'action_to_do' => FormConstant::UPDATE_LABEL_TEXT,
			]);
		}
	}
