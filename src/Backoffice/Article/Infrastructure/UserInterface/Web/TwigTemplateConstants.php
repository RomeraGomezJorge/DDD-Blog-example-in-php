<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Infrastructure\UserInterface\Web;
	
	final class TwigTemplateConstants
	{
		const LIST_PATH = 'article_list';
		const EDIT_PATH = 'article_edit';
		const ADD_PATH = 'article_add';
		const CREATE_PATH = 'article_create';
		const UPDATE_PATH = 'article_update';
		const DELETE_PATH = 'article_delete';
		const ATTACHMENT_DELETE_MODAL_CONFIRMATION_PATH = 'article_attachment_delete_confirmation_modal';
		const ATTACHMENT_DELETE_PATH = 'article_attachment_delete';
		const TITLE_AVAILABLE_PATH = 'article_title_available';
		const SECTION_TITLE = 'Publicación';
		const FORM_FILE_PATH = 'backoffice/article/formToHandleItem.html.twig';
		const LIST_FILE_PATH = 'backoffice/article/list.html.twig';
		const ALLOWED_FILES = 'image/png,image/x-png,image/jpeg,image/jpg,audio/mp3,audio/mpeg,application/msword,application/vnd.ms-excel,application/vnd.ms-powerpoint,text/plain,application/pdf,.xls,.xlsx,.xlsm';
	}