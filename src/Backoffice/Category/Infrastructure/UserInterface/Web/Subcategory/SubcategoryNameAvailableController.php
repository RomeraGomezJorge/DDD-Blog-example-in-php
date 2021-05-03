<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Subcategory;
	
	use App\Backoffice\Category\Application\Get\Single\CheckCategoryNameAvailability;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	
	final class SubcategoryNameAvailableController extends WebController
	{
		public function __invoke(
			Request $request,
			CheckCategoryNameAvailability $checkCategoryNameAvailability
		): JsonResponse {
			return new JsonResponse(
				$checkCategoryNameAvailability->__invoke(
					$request->get('name'),
					$request->get('parent_id')
				)
			);
		}
	}