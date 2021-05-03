<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Category;
	
	final class TwigTemplateConstants
	{
		const LIST_PATH = 'category_list';
		const EDIT_PATH = 'category_edit';
		const ADD_PATH = 'category_add';
		const CREATE_PATH = 'category_create';
		const CREATE_BY_AJAX_PATH = 'category_create_by_ajax';
		const ADD_CATEGORY_BY_MODAL_PATH = 'category_add_by_modal';
		const UPDATE_PATH = 'category_update';
		const DELETE_PATH = 'category_delete';
		const NAME_AVAILABLE_PATH = 'category_name_available';
		const POSITION_AVAILABLE_PATH = 'category_position_available';
		const SECTION_TITLE = 'Categoria';
		const FORM_FILE_PATH = 'backoffice/category/formToHandleItem.html.twig';
		const LIST_FILE_PATH = 'backoffice/category/list.html.twig';
		const ADD_CATEGORY_BY_MODAL_FILE_PATH = 'backoffice/category/add_category_by_modal.html.twig';
	}