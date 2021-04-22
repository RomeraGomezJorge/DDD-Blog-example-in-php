<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\User\Application\Get\Single\CheckUserEmailAvailability;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	
	final class UserEmailAvailableController extends WebController
	{
		public function __invoke(
			Request $request,
			CheckUserEmailAvailability $userEmailAvailabilityChecker
		): JsonResponse {
			return new JsonResponse(
				$userEmailAvailabilityChecker->__invoke($request->get('email', ''))
			);
		}
	}