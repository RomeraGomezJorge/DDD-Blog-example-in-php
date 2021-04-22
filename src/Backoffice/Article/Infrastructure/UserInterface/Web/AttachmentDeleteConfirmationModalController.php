<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Infrastructure\UserInterface\Web;
	
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\HttpFoundation\Request;
	
	final class AttachmentDeleteConfirmationModalController extends WebController
	{
		public function __invoke(Request $request): JsonResponse
		{
			$html = $this->render('backoffice/article/form/attachmentDeleteConfirmModalPopup.html.twig', [
				'id' => $request->get('id'),
				'attachment' => $request->get('attachment'),
				'delete_path' => $request->get('delete_path'),
				'css_selector_to_handle_tr_style_that_contains_items_to_delete' => $request->get('css_selector_to_handle_tr_style_that_contains_items_to_delete'),
				'data_about_item' => $request->get('data_about_item')
			])->getContent();
			
			return new JsonResponse(array('status' => 'success', 'html' => $html));
		}
	}