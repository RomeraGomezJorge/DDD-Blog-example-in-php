<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Category;
	
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\CategoryRepository;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	use App\Tests\Shared\Infrastructure\PhpUnit\ContextInfrastructureTestCase;
	
	class CategoryInfrastructureTestCase extends ContextInfrastructureTestCase
	{
		const LIST_CATEGORY_ITEMS_PATH = '/backoffice/category/list';
		const LIST_SUBCATEGORY_ITEMS_PATH = '/backoffice/subcategory/list';
		const CREATE_CATEGORY_ITEM_PATH = '/backoffice/category/create';
		const CREATE_SUBCATEGORY_ITEM_PATH = '/backoffice/subcategory/create';
		const EDIT_CATEGORY_ITEM_PATH = '/backoffice/category/edit';
		const EDIT_SUBCATEGORY_ITEM_PATH = '/backoffice/subcategory/edit';
		const LABEL_TO_CREATE_CATEGORY_ITEMS = 'Crear Categoria';
		const LABEL_TO_CREATE_SUBCATEGORY_ITEMS = 'Crear Subcategoria';
		const LABEL_TO_UPDATE_CATEGORY_ITEMS = 'Actualizar Categoria';
		const LABEL_TO_UPDATE_SUBCATEGORY_ITEMS = 'Actualizar Subcategoria';
		const PARENT_CATEGORY_IS_NOT_DEFINED_AS_FILTER = null;
		const DESCRIPTION_IS_AVAILABLE = true;
		const DESCRIPTION_IS_NOT_AVAILABLE = false;
		const POSITION_IS_AVAILABLE = true;
		const POSITION_IS_NOT_AVAILABLE = false;
		
		protected function getRandomSubcategoryFromDatabase(): Category
		{
			$category = $this->getRandomCategoryFromDatabase();
			
			$subcategory = CategoryMother::randomWithParentCategory($category);
			
			$this->repository()->save($subcategory);
			
			$this->clearUnitOfWork();
			
			return $this->repository()->search(new Uuid($subcategory->id()));
		}
		
		protected function getRandomCategoryFromDatabase(): Category
		{
			$category = CategoryMother::random();
			
			$this->saveCategoryAndClearUnitOfWork($category);
			
			return $this->repository()->search(new Uuid($category->id()));
		}
		
		protected function saveCategoryAndClearUnitOfWork(Category $category)
		{
			$this->repository()->save($category);
			
			$this->clearUnitOfWork();
		}
		
		protected function repository(): CategoryRepository
		{
			return $this->service(CategoryRepository::class);
		}
	}