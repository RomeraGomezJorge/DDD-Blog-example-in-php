<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Infrastructure\UserInterface\Web;
	
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	
	final class ResetPasswordModalPopupController extends WebController
	{
		public function __invoke(Request $request): JsonResponse
		{
			$html = $this->render('backoffice/user/resetPassword.html.twig', [
				'id' => $request->get('id'),
				'form_action' => TwigTemplateConstants::RESET_PASSWORD_PATH
			])->getContent();
			
			return new JsonResponse(array('status' => 'success', 'html' => $html));
		}
	}