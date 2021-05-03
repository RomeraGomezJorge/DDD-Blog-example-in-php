<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Subcategory;
	
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use App\Backoffice\Category\Application\Put\CategoryDetailsChanger;
	use App\Shared\Infrastructure\Constant\MessageConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class SubcategoryPutController extends WebController
	{
		public function __invoke(Request $request, CategoryDetailsChanger $updater): Response
		{
			$isCsrfTokenValid = $this->isCsrfTokenValid($request->get('id'), $request->get('csrf_token'));
			
			if (!$isCsrfTokenValid) {
				return $this->redirectWithMessage('error_page', MessageConstant::INVALID_TOKEN_CSFR_MESSAGE);
			}
			
			$validationErrors = ValidationRulesToCreateAndUpdate::verify($request);
			
			return $validationErrors->count() !== 0
				? $this->redirectWithErrors(TwigTemplateConstants::EDIT_PATH, $validationErrors, $request)
				: $this->update($request, $updater);
		}
		
		private function update(Request $request, CategoryDetailsChanger $updater): RedirectResponse
		{
			$updater->__invoke(
				$request->get('id'),
				$request->get('name'),
				$request->get('description'),
				(int)$request->get('position'),
				$request->get('parent_id')
			);
			
			return $this->redirectWithMessage(
				TwigTemplateConstants::LIST_PATH,
				MessageConstant::SUCCESS_MESSAGE_TO_UPDATE
			);
		}
	}
