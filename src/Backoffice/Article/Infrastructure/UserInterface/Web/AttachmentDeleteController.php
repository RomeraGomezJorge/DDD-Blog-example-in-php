<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Infrastructure\UserInterface\Web;
	
	
	use App\Backoffice\Article\Application\Delete\AttachmentDeleter;
	use App\Shared\Infrastructure\Constant\MessageConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Validator\Constraints as Assert;
	use Symfony\Component\Validator\ConstraintViolationListInterface;
	use Symfony\Component\Validator\Validation;
	
	class AttachmentDeleteController extends WebController
	{
		public function __invoke(Request $request, AttachmentDeleter $deleter): JsonResponse
		{
			$isCsrfTokenValid = $this->isCsrfTokenValid($request->get('id'), $request->get('csrf_token'));
			
			if (!$isCsrfTokenValid) {
				return new JsonResponse(array(
					'status' => 'fail_invalid_csfr_token',
					'message' => MessageConstant::INVALID_TOKEN_CSFR_MESSAGE
				));
			}
			
			$validationErrors = $this->validationRules($request);
			
			$response = $validationErrors->count() !== 0 ?
				array('status' => 'fail', 'message' => MessageConstant::UNEXPECTED_ERROR_HAS_OCCURRED) :
				$this->delete($deleter, $request);
			
			return new JsonResponse($response);
		}
		
		public function validationRules($request): ConstraintViolationListInterface
		{
			$constraint = new Assert\Collection(
				[
					'id' => new Assert\Uuid(),
					'attachment' => new Assert\Json(),
					'csrf_token' => new Assert\NotBlank(),
					'css_selector_to_handle_tr_style_that_contains_items_to_delete' => new Assert\NotBlank()
				]
			);
			
			$input = $request->request->all();
			
			return Validation::createValidator()->validate($input, $constraint);
		}
		
		private function delete(AttachmentDeleter $deleter, Request $request): array
		{
			$deleter->__invoke($request->get('id'), $request->get('attachment'));
			
			return array('status' => 'success');
		}
	}
	
