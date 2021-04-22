<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Category;
	
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	
	final class CategoryAddModalController extends WebController
	{
		public function __invoke(FormToCreateACategoryByAjax $formToCreateACategory): JsonResponse
		{
			$html = $formToCreateACategory->__invoke();
			
			return new JsonResponse(array('status' => 'success', 'html' => $html));
		}
	}