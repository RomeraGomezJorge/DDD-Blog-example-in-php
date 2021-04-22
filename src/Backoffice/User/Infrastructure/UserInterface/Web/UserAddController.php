<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\Role\Domain\RoleRepository;
	use App\Shared\Infrastructure\Constant\FormConstant;
	use App\Shared\Infrastructure\RamseyUuidGenerator;
	use App\Shared\Infrastructure\Symfony\FlashSession;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Response;
	
	class UserAddController extends WebController
	{
		public function __invoke(
			FlashSession $flashSession,
			RamseyUuidGenerator $ramseyUuidGenerator,
			RoleRepository $roleRepository
		): Response {
			return $this->render(TwigTemplateConstants::FORM_FILE_PATH, [
				'list_path' => TwigTemplateConstants::LIST_PATH,
				'page_title' => TwigTemplateConstants::SECTION_TITLE,
				'user_name_available_path' => TwigTemplateConstants::USER_NAME_AVAILABLE_PATH,
				'email_available_path' => TwigTemplateConstants::EMAIL_AVAILABLE_PATH,
				'id' => $ramseyUuidGenerator->generate(),
				'username' => $flashSession->get('inputs.username'),
				'name' => $flashSession->get('inputs.name'),
				'surname' => $flashSession->get('inputs.surname'),
				'email' => $flashSession->get('inputs.email'),
				'password' => '',
				'role_id' => $flashSession->get('inputs.role_id'),
				'is_active' => $flashSession->get('inputs.is_active'),
				'roles' => $roleRepository->searchAll(),
				'form_action_attribute' => TwigTemplateConstants::CREATE_PATH,
				'submit_button_label' => FormConstant::SUBMIT_BUTTON_VALUE_TO_CREATE,
				'action_to_do' => FormConstant::CREATE_LABEL_TEXT,
			]);
		}
	}
