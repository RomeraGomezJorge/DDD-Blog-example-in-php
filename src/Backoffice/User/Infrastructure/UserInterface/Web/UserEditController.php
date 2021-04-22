<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\Role\Domain\RoleRepository;
	use App\Backoffice\User\Application\Get\Single\UserFinder;
	use App\Shared\Infrastructure\Constant\FormConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class UserEditController extends WebController
	{
		public function __invoke(
			Request $request,
			UserFinder $finder,
			RoleRepository $roleRepository
		): Response {
			$user = $finder->__invoke($request->get('id'));
			
			return $this->render(TwigTemplateConstants::FORM_FILE_PATH, [
				'page_title' => TwigTemplateConstants::SECTION_TITLE,
				'list_path' => TwigTemplateConstants::LIST_PATH,
				'user_name_available_path' => TwigTemplateConstants::USER_NAME_AVAILABLE_PATH,
				'email_available_path' => TwigTemplateConstants::EMAIL_AVAILABLE_PATH,
				'reset_password_modal_path' => TwigTemplateConstants::RESET_PASSWORD_MODAL_PATH,
				'id' => $user->getId(),
				'username' => $user->getUsername(),
				'name' => $user->getName(),
				'surname' => $user->getSurname(),
				'email' => $user->getEmail(),
				'role_id' => $user->getRole()->getId(),
				'is_active' => $user->getIsActive(),
				'roles' => $roleRepository->searchAll(),
				'form_action_attribute' => TwigTemplateConstants::UPDATE_PATH,
				'submit_button_label' => FormConstant::SUBMIT_BUTTON_VALUE_TO_UPDATE,
				'action_to_do' => FormConstant::UPDATE_LABEL_TEXT,
			]);
		}
	}
