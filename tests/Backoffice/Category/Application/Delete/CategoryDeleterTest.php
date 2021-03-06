<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Category\Application\Delete;
	
	use App\Backoffice\Category\Application\Delete\CategoryDeleter;
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\Exception\CategoryNotExist;
	use App\Tests\Backoffice\Category\CategoryModuleUnitTestCase;
	use App\Tests\Backoffice\Category\Domain\CategoryMother;
	
	final class CategoryDeleterTest extends CategoryModuleUnitTestCase
	{
		private CategoryDeleter $deleter;
		private Category $category;
		
		/** @test */
		public function it_should_delete_an_existing_category(): void
		{
			$this->shouldFind($this->category);
			
			$this->repository()
				->shouldReceive('delete')
				->once()
				->with($this->similarTo($this->category));
			
			$this->deleter->__invoke($this->category->id());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_category_does_not_exit(): void
		{
			$this->expectException(CategoryNotExist::class);
			
			$this->shouldNotFind($this->category);
			
			$this->repository()
				->shouldReceive('delete')
				->never();
			
			$this->deleter->__invoke($this->category->id());
		}
		
		protected function setUp(): void
		{
			parent::setUp(); // TODO: Change the autogenerated stub
			
			$this->deleter = new CategoryDeleter($this->repository());
			
			$this->category = CategoryMother::random();
		}
	}