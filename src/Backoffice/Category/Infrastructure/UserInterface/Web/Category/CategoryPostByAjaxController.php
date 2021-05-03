<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Category;
	
	use App\Backoffice\Category\Application\Post\CategoryCreator;
	use App\Shared\Infrastructure\Constant\MessageConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Validator\Constraints as Assert;
	use Symfony\Component\Validator\ConstraintViolationListInterface;
	use Symfony\Component\Validator\Validation;
	
	class CategoryPostByAjaxController extends WebController
	{
		const PARENT_CATEGORY_IS_NOT_DEFINED = null;
		
		public function __invoke(
			Request $request,
			CategoryCreator $creator,
			FormToCreateACategoryByAjax $formToCreateACategory
		): JsonResponse {
			$isCsrfTokenValid = $this->isCsrfTokenValid($request->get('id'), $request->get('csrf_token'));
			
			if (!$isCsrfTokenValid) {
				return $this->failResponse(MessageConstant::INVALID_TOKEN_CSFR_MESSAGE);
			}
			
			$validationErrors = $this->validateRequest($request);
			
			return $validationErrors->count() !== 0
				? $this->jsonResponseWithErrors($formToCreateACategory, $validationErrors, $request)
				: $this->create($request, $creator);
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
					'name' => [new Assert\NotBlank(), new Assert\Length(['max' => 255])],
					'position' => [new Assert\NotBlank(), new Assert\GreaterThan(0)],
					'description' => [new Assert\Length(['max' => 255])],
					'select_this_category_on_save' => [new Assert\Optional()],
					'csrf_token' => [new Assert\NotBlank()]
				]
			);
			
			$input = $request->request->all();
			
			return Validation::createValidator()->validate($input, $constraint);
		}
		
		private function create(Request $request, CategoryCreator $creator): JsonResponse
		{
			$creator->__invoke(
				$request->get('id'),
				$request->get('name'),
				$request->get('description'),
				(int)$request->get('position'),
				$request->get('parentId', self::PARENT_CATEGORY_IS_NOT_DEFINED)
			);
			
			return JsonResponse::create(array('status' => 'success'));
		}
	}
