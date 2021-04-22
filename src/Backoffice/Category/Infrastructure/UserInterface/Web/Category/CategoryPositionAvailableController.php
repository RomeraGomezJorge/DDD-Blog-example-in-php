<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Category;
	
	use App\Backoffice\Category\Application\Get\Single\CheckCategoryPositionAvailability;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	
	final class CategoryPositionAvailableController extends WebController
	{
		public function __invoke(
			Request $request,
			CheckCategoryPositionAvailability $checkCategoryPositionAvailability
		): JsonResponse {
			return new JsonResponse(
				$checkCategoryPositionAvailability->__invoke($request->get('position'))
			);
		}
	}