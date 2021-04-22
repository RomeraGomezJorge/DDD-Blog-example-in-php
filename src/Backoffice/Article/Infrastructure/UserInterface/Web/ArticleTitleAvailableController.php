<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\Article\Application\Get\Single\CheckArticleTitleAvailability;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	
	final class ArticleTitleAvailableController extends WebController
	{
		public function __invoke(
			Request $request,
			CheckArticleTitleAvailability $checkArticleTitleAvailability
		): JsonResponse {
			return new JsonResponse($checkArticleTitleAvailability->__invoke($request->get('title', '')));
		}
	}