<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\Author\Application\Post\AuthorCreator;
	use App\Shared\Infrastructure\Constant\MessageConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Validator\Constraints as Assert;
	use Symfony\Component\Validator\ConstraintViolationListInterface;
	use Symfony\Component\Validator\Validation;
	
	class AuthorPostByAjaxController extends WebController
	{
		public function __invoke(
			Request $request,
			AuthorCreator $authorCreator,
			FormToCreateAnAuthorByAjax $formToCreateAnAuthorByAjax
		): JsonResponse {
			$isCsrfTokenValid = $this->isCsrfTokenValid($request->get('id'), $request->get('csrf_token'));
			
			if (!$isCsrfTokenValid) {
				return JsonResponse::create(array(
					'status' => 'fail',
					'message' => MessageConstant::INVALID_TOKEN_CSFR_MESSAGE
				));
			}
			
			$validationErrors = $this->validateRequest($request);
			
			return $validationErrors->count() !== 0
				? $this->jsonResponseWithErrors($formToCreateAnAuthorByAjax, $validationErrors, $request)
				: $this->createAuthor($request, $authorCreator);
		}
		
		private function validateRequest(Request $request): ConstraintViolationListInterface
		{
			$constraint = new Assert\Collection(
				[
					'id' => new Assert\Uuid(),
					'fullname' => [new Assert\NotBlank(), new Assert\Length(['min' => 1, 'max' => 100])],
					'biography' => [new Assert\Optional(), new Assert\Length(['max' => 255])],
					'select_this_author_on_save' => [new Assert\Optional()],
					'csrf_token' => [new Assert\NotBlank()]
				]
			);
			
			$input = $request->request->all();
			
			return Validation::createValidator()->validate($input, $constraint);
		}
		
		private function createAuthor(Request $request, AuthorCreator $authorCreator): JsonResponse
		{
			$authorCreator->__invoke(
				$request->get('id'),
				$request->get('fullname'),
				$request->get('biography')
			);
			
			return JsonResponse::create(array('status' => 'success'));
		}
	}
