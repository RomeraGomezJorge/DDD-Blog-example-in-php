<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Subcategory;
	
	final class TwigTemplateConstants
	{
		const LIST_PATH = 'subcategory_list';
		const EDIT_PATH = 'subcategory_edit';
		const ADD_PATH = 'subcategory_add';
		const CREATE_PATH = 'subcategory_create';
		const UPDATE_PATH = 'subcategory_update';
		const DELETE_PATH = 'subcategory_delete';
		const NAME_AVAILABLE_PATH = 'subcategory_name_available';
		const POSITION_AVAILABLE_PATH = 'subcategory_position_available';
		const SECTION_TITLE = 'Subcategoria';
		const FORM_FILE_PATH = 'backoffice/subcategory/formToHandleItem.html.twig';
		const LIST_FILE_PATH = 'backoffice/subcategory/list.html.twig';
	}