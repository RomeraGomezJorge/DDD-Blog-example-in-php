<?php
	
	namespace App\Backoffice\Authentication\ResetPasswordRequest\Infrastructure\UserInterface\Web;
	
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Response;
	use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
	
	class EmailMessageSentController extends WebController
	{
		use ResetPasswordControllerTrait;
		
		/* Confirmation page after a user has requested a password reset.*/
		public function __invoke(): Response
		{
			/*We prevent users from directly accessing this page. */
			if (null === ($resetToken = $this->getTokenObjectFromSession())) {
				return $this->redirectToRoute(TwigTemplateConstants::FORGOT_PASSWORD_REQUEST_PATH);
			}
			
			return $this->render(TwigTemplateConstants::EMAIL_MESSAGE_SENT_FILE_PATH, [
				'goBackLink' => TwigTemplateConstants::FORGOT_PASSWORD_REQUEST_PATH,
				'emailMessageSentImage' => TwigTemplateConstants::EMAIL_MESSAGE_SENT_IMAGE,
				'resetToken' => $resetToken
			]);
		}
	}
