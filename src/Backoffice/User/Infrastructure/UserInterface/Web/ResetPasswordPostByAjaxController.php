<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\User\Application\Patch\UserPasswordReset;
	use App\Shared\Infrastructure\Constant\MessageConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Validator\Constraints as Assert;
	use Symfony\Component\Validator\ConstraintViolationListInterface;
	use Symfony\Component\Validator\Validation;
	
	class ResetPasswordPostByAjaxController extends WebController
	{
		public function __invoke(Request $request, UserPasswordReset $userPasswordReset): JsonResponse
		{
			$isCsrfTokenValid = $this->isCsrfTokenValid($request->get('id'), $request->get('csrf_token'));
			
			if (!$isCsrfTokenValid) {
				return $this->failResponse(MessageConstant::INVALID_TOKEN_CSFR_MESSAGE);
			}
			
			$validationErrors = $this->validateRequest($request);
			
			return $validationErrors->count() !== 0
				? $this->failResponse()
				: $this->update($request, $userPasswordReset);
		}
		
		private function failResponse($message = ''): JsonResponse
		{
			return JsonResponse::create(array(
				'status' => 'fail',
				'message' => $message
			));
		}
		
		private function validateRequest(Request $request): ConstraintViolationListInterface
		{
			$constraint = new Assert\Collection(
				[
					'id' => [new Assert\Uuid()],
					'password' => [new Assert\Length(['min' => 8, 'max' => 255])],
					'csrf_token' => [new Assert\NotBlank()]
				]
			);
			
			$input = $request->request->all();
			
			return Validation::createValidator()->validate($input, $constraint);
		}
		
		private function update(Request $request, UserPasswordReset $passwordReset): JsonResponse
		{
			$passwordReset->__invoke(
				$request->get('id'),
				$request->get('password'),

			);
			
			return JsonResponse::create(array('status' => 'success'));
		}
	}
