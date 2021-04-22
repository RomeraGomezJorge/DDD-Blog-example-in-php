<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\ErrorPage\Infrastructure\UserInterface\Web;
	
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Response;
	
	class ErrorPageGetController extends WebController
	{
		public function __invoke(): Response
		{
			return $this->render('backoffice/errorPage/errorPage.html.twig');
		}
	}
