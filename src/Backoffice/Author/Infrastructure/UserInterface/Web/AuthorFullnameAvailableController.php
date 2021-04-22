<?php
	
	declare(strict_types=1);
	
	
	namespace App\Backoffice\Author\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\Author\Application\Get\Single\CheckAuthorFullnameAvailability;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	
	final class AuthorFullnameAvailableController extends WebController
	{
		public function __invoke(
			Request $request,
			CheckAuthorFullnameAvailability $checkAuthorFullnameAvailability
		): JsonResponse {
			return new JsonResponse(
				$checkAuthorFullnameAvailability->__invoke($request->get('fullname', ''))
			);
		}
	}