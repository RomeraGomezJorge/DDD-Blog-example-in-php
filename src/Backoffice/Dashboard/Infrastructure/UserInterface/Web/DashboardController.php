<?php
	
	namespace App\Backoffice\Dashboard\Infrastructure\UserInterface\Web;
	
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class DashboardController extends WebController
	{
		public function __invoke(Request $request): Response
		{
			return $this->render('backoffice/dashboard/index.html.twig');
		}
	}
