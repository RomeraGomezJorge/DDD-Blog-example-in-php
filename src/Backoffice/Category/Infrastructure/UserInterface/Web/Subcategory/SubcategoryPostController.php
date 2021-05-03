<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Subcategory;
	
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use App\Backoffice\Category\Application\Post\CategoryCreator;
	use App\Shared\Infrastructure\Constant\MessageConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class SubcategoryPostController extends WebController
	{
		public function __invoke(Request $request, CategoryCreator $creator): Response
		{
			$isCsrfTokenValid = $this->isCsrfTokenValid($request->get('id'), $request->get('csrf_token'));
			
			if (!$isCsrfTokenValid) {
				return $this->redirectWithMessage('error_page', MessageConstant::INVALID_TOKEN_CSFR_MESSAGE);
			}
			
			$validationErrors = ValidationRulesToCreateAndUpdate::verify($request);
			
			return $validationErrors->count() !== 0
				? $this->redirectWithErrors(TwigTemplateConstants::ADD_PATH, $validationErrors, $request)
				: $this->createCategory($request, $creator);
		}
		
		private function createCategory(Request $request, CategoryCreator $creator): RedirectResponse
		{
			$creator->__invoke(
				$request->get('id'),
				$request->get('name'),
				$request->get('description'),
				(int)$request->get('position'),
				$request->get('parent_id')
			);
			
			return $this->redirectWithMessage(
				TwigTemplateConstants::LIST_PATH,
				MessageConstant::SUCCESS_MESSAGE_TO_CREATE
			);
		}
	}
