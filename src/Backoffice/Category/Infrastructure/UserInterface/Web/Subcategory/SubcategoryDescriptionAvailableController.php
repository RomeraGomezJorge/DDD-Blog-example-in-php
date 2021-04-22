<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Subcategory;
	
	use App\Backoffice\Category\Application\Get\Single\CheckCategoryDescriptionAvailability;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	
	final class SubcategoryDescriptionAvailableController extends WebController
	{
		public function __invoke(
			Request $request,
			CheckCategoryDescriptionAvailability $checkCategoryDescriptionAvailability
		): JsonResponse {
			return new JsonResponse(
				$checkCategoryDescriptionAvailability->__invoke(
					$request->get('description'),
					$request->get('parent_id')
				)
			);
		}
	}