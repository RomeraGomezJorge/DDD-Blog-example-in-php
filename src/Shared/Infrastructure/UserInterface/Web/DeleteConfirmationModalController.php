<?php
	
	
	namespace App\Shared\Infrastructure\UserInterface\Web;
	
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	
	
	final class DeleteConfirmationModalController extends WebController
	{
		public function __invoke(Request $request): JsonResponse
		{
			$html = $this->render('backoffice/_shared/deleteConfirmModalPopup.html.twig', [
				'id' => $request->get('id'),
				'delete_path' => $request->get('delete_path'),
				'css_selector_to_handle_tr_style_that_contains_items_to_delete' => $request->get('css_selector_to_handle_tr_style_that_contains_items_to_delete'),
				'data_about_item' => $request->get('data_about_item')
			])->getContent();
			
			return new JsonResponse(array('status' => 'success', 'html' => $html));
		}
	}