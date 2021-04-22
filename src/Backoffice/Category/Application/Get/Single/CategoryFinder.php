<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Application\Get\Single;
	
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\CategoryRepository;
	use App\Backoffice\Category\Domain\Exception\CategoryNotExist;
	use App\Shared\Domain\ValueObject\Uuid;
	
	final class CategoryFinder
	{
		const NOT_FOUND = null;
		private CategoryRepository $repository;
		
		public function __construct(CategoryRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function __invoke(string $id): Category
		{
			$id = new Uuid($id);
			
			$category = $this->repository->search($id);
			
			if ($category === self::NOT_FOUND) {
				throw new CategoryNotExist($id->value());
			}
			
			return $category;
		}
	}