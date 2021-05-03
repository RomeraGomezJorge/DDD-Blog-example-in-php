<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Category;
	
	use App\Backoffice\Category\Application\Get\Single\CategoryFinder;
	use App\Shared\Infrastructure\Constant\FormConstant;
	use App\Shared\Infrastructure\Symfony\WebController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	
	class CategoryEditController extends WebController
	{
		public function __invoke(Request $request, CategoryFinder $finder): Response
		{
			$category = $finder->__invoke($request->get('id'));
			
			return $this->render(TwigTemplateConstants::FORM_FILE_PATH, [
				'page_title' => TwigTemplateConstants::SECTION_TITLE,
				'list_path' => TwigTemplateConstants::LIST_PATH,
				'name_available_path' => TwigTemplateConstants::NAME_AVAILABLE_PATH,
				'position_available_path' => TwigTemplateConstants::POSITION_AVAILABLE_PATH,
				'id' => $category->id(),
				'name' => $category->name(),
				'description' => $category->description(),
				'position' => $category->position(),
				'form_action_attribute' => TwigTemplateConstants::UPDATE_PATH,
				'submit_button_label' => FormConstant::SUBMIT_BUTTON_VALUE_TO_UPDATE,
				'action_to_do' => FormConstant::UPDATE_LABEL_TEXT,
			]);
		}
	}
