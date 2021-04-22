<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Infrastructure\UserInterface\Web;
	
	use App\Backoffice\User\Application\Get\Single\CheckUsernameAvailability;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	
	final class UserNameAvailableController extends WebController
	{
		public function __invoke(Request $request, CheckUsernameAvailability $checkUsernameAvailability): JsonResponse
		{
			return new JsonResponse(
				$checkUsernameAvailability->__invoke($request->get('username', ''))
			);
		}
	}