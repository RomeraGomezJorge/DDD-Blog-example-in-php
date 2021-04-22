<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Infrastructure\UserInterface\Web;
	
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	
	final class AuthorAddModalContentController extends WebController
	{
		public function __invoke(FormToCreateAnAuthorByAjax $formToCreateAnAuthorByAjax): JsonResponse
		{
			$html = $formToCreateAnAuthorByAjax->__invoke();
			
			return new JsonResponse(array('status' => 'success', 'html' => $html));
		}
	}