<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Infrastructure\UserInterface\Web;
	
	final class TwigTemplateConstants
	{
		const LIST_PATH = 'author_list';
		const EDIT_PATH = 'author_edit';
		const ADD_PATH = 'author_add';
		const CREATE_PATH = 'author_create';
		const UPDATE_PATH = 'author_update';
		const DELETE_PATH = 'author_delete';
		const FULLNAME_AVAILABLE_PATH = 'author_fullname_available';
		const SECTION_TITLE = 'Autor';
		const FORM_FILE_PATH = 'backoffice/author/formToHandleItem.html.twig';
		const LIST_FILE_PATH = 'backoffice/author/list.html.twig';
		const CREATE_BY_AJAX_PATH = 'author_create_by_ajax';
		const ADD_AUTHOR_BY_MODAL_PATH = 'author_add_by_modal';
	}