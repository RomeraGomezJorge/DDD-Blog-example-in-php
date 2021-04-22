<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Author;
	
	use App\Backoffice\Author\Domain\AuthorRepository;
	use App\Tests\Shared\Infrastructure\PhpUnit\ContextInfrastructureTestCase;
	
	class AuthorInfrastructureTestCase extends ContextInfrastructureTestCase
	{
		const LIST_ITEMS_PATH = '/backoffice/author/list';
		const CREATE_ITEM_PATH = '/backoffice/author/create';
		const EDIT_ITEM_PATH = '/backoffice/author/edit';
		const LABEL_TO_CREATE_ITEMS = 'Crear Autor';
		const LABEL_TO_UPDATE_ITEMS = 'Actualizar Autor';
		
		protected function repository(): AuthorRepository
		{
			return $this->service(AuthorRepository::class);
		}
	}