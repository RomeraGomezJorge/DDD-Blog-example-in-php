<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\User\Application\Get\Single\UserFinder;
	use App\Shared\Infrastructure\Constant\FormConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Response;
	
	class AccountManagementGetController extends WebController
	{
		public function __invoke(UserFinder $finder): Response
		{
			$loggedUser = $this->getUser();
			
			$userFound = $finder->__invoke($loggedUser->getId());
			
			return $this->render(TwigTemplateConstants::ACCOUNT_MANAGEMENT_FILE_PATH, [
				'page_title' => 'Gestionar Cuenta',
				'list_path' => TwigTemplateConstants::LIST_PATH,
				'user_name_available_path' => TwigTemplateConstants::USER_NAME_AVAILABLE_PATH,
				'email_available_path' => TwigTemplateConstants::EMAIL_AVAILABLE_PATH,
				'reset_password_modal_path' => TwigTemplateConstants::RESET_PASSWORD_MODAL_PATH,
				'id' => $userFound->getId(),
				'username' => $userFound->getUsername(),
				'name' => $userFound->getName(),
				'surname' => $userFound->getSurname(),
				'email' => $userFound->getEmail(),
				'form_action_attribute' => TwigTemplateConstants::ACCOUNT_UPDATE_PATH,
				'submit_button_label' => FormConstant::SUBMIT_BUTTON_VALUE_TO_UPDATE,
				'action_to_do' => '',
			]);
		}
	}
