<?php
	
	namespace App\Backoffice\Authentication\ResetPasswordRequest\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\User\Application\Patch\UserPasswordReset;
	use App\Shared\Infrastructure\Constant\MessageConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\Validator\Constraints as Assert;
	use Symfony\Component\Validator\ConstraintViolationListInterface;
	use Symfony\Component\Validator\Validation;
	use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
	use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
	
	
	class NewPasswordPostController extends WebController
	{
		use ResetPasswordControllerTrait;
		private $resetPasswordHelper;
		private UserPasswordReset $userPasswordReset;
		
		public function __construct(
			ResetPasswordHelperInterface $resetPasswordHelper,
			UserPasswordReset $userPasswordReset
		) {
			$this->resetPasswordHelper = $resetPasswordHelper;
			$this->userPasswordReset = $userPasswordReset;
		}
		
		public function __invoke(Request $request): Response
		{
			$isCsrfTokenValid = $this->isCsrfTokenValid($request->get('id'), $request->get('csrf_token'));
			
			if (!$isCsrfTokenValid) {
				$this->addFlash('reset_password_error',
					'<h4 class="alert-heading text-danger>' . MessageConstant::INVALID_TOKEN_CSFR_MESSAGE . '</h4>'
				);
				
				return $this->redirectToRoute(TwigTemplateConstants::RESET_PASSWORD_PATH);
			}
			
			
			$validationErrors = $this->validateRequest($request);
			
			return $validationErrors->count() !== 0
				? $this->render(TwigTemplateConstants::FORM_TO_HANDLE_NEW_PASSWORD_FILE_PATH)
				: $this->update($request);
		}
		
		private function validateRequest(Request $request): ConstraintViolationListInterface
		{
			$constraint = new Assert\Collection(
				[
					'id' => [new Assert\Uuid()],
					'password' => [new Assert\Length(['min' => 8, 'max' => 255]), new Assert\NotBlank()],
					'csrf_token' => [new Assert\NotBlank()]
				]
			);
			
			$input = $request->request->all();
			
			return Validation::createValidator()->validate($input, $constraint);
		}
		
		/*The token is valid; allows the user to change their password. */
		private function update(Request $request): Response
		{
			$this->resetPasswordHelper->removeResetRequest($this->getTokenFromSessionOrFail());
			
			$this->userPasswordReset->__invoke(
				$request->get('id'),
				$request->get('password')
			);
			
			/* The session is cleaned up after changing the password. */
			$this->cleanSessionAfterReset();
			
			$this->addFlash('reset_password_success',
				'<i class="fas fa-check-circle text-success"></i> La contraseña se restableció con exito.'
			);
			
			
			return $this->redirectToRoute('login');
		}
		
		private function getTokenFromSessionOrFail(): string
		{
			$token = $this->getTokenFromSession();
			
			
			if (null === $token) {
				throw $this->createNotFoundException('No se encuentra ninguna contraseña de restablecimiento en la URL o en la sesión.');
			}
			
			return $token;
		}
	}
