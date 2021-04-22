<?php
	
	namespace App\Backoffice\Authentication\ResetPasswordRequest\Infrastructure\UserInterface\Web;
	
	
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Response;
	
	
	class ResetPasswordRequestController extends WebController
	{
		/**
		 * Mostrar formulario para solicitar un restablecimiento de contraseña.
		 */
		public function __invoke(): Response
		{
			return $this->render(TwigTemplateConstants::FORM_TO_HANDLE_RESET_PASSWORD_REQUEST_FILE_PATH,
				['goBackLink' => TwigTemplateConstants::FORGOT_PASSWORD_REQUEST_PATH]);
		}
	}
