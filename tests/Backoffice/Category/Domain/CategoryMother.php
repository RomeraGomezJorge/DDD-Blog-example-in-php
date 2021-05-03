<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Category\Domain;
	
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\CategoryNameIsAvailableSpecification;
	use App\Backoffice\Category\Domain\CategoryPositionIsAvailableSpecification;
	use App\Backoffice\Category\Domain\ValueObject\CategoryName;
	use App\Backoffice\Category\Domain\ValueObject\CategoryPosition;
	use App\Shared\Domain\SlugGenerator;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Backoffice\Author\Domain\AuthorMother;
	use App\Tests\Shared\Domain\IntegerMother;
	use App\Tests\Shared\Domain\WordMother;
	use DateTime;
	use PHPUnit\Framework\TestCase;
	
	
	final class CategoryMother extends testCase
	{
		const DESCRIPTION_IS_AVAILABLE = true;
		const POSITION_IS_AVAILABLE = true;
		const WITHOUT_PARENT_CATEGORY = null;
		
		public static function random(): Category
		{
			return self::create(
				Uuid::random(),
				WordMother::random(),
				IntegerMother::between(0, 2147483647),
				self::WITHOUT_PARENT_CATEGORY,
				self::DESCRIPTION_IS_AVAILABLE,
				self::POSITION_IS_AVAILABLE
			
			);
		}
		
		public static function create(
			Uuid $id,
			string $description,
			int $position,
			?Category $category,
			bool $isDescriptionAvailable,
			bool $isPositionAvailable
		): Category {
			$categoryDescriptionIsAvailableSpecificationStub = (new CategoryMother)->uniqueCategoryDescriptionSpecificationStub();
			$categoryDescriptionIsAvailableSpecificationStub->method('isSatisfiedBy')->willReturn($isDescriptionAvailable);
			
			$categoryPositionIsAvailableSpecificationStub = (new CategoryMother)->uniqueCategoryPositionSpecificationStub();
			$categoryPositionIsAvailableSpecificationStub->method('isSatisfiedBy')->willReturn($isPositionAvailable);
			
			$slugGeneratorStub = (new AuthorMother())->slugGeneratorStub();
			$slugGeneratorStub->method('generate');
			
			return Category::create(
				$id,
				new CategoryName($description),
				new CategoryPosition($position),
				$category, new DateTime(),
				$categoryDescriptionIsAvailableSpecificationStub,
				$categoryPositionIsAvailableSpecificationStub,
				$slugGeneratorStub
			);
		}
		
		public function uniqueCategoryDescriptionSpecificationStub()
		{
			return $this->createMock(CategoryNameIsAvailableSpecification::class);
		}
		
		public function uniqueCategoryPositionSpecificationStub()
		{
			return $this->createMock(CategoryPositionIsAvailableSpecification::class);
		}
		
		public function slugGeneratorStub()
		{
			return $this->createMock(SlugGenerator::class);
		}
		
		public static function randomWithDescription($description): Category
		{
			return self::create(
				Uuid::random(),
				$description,
				IntegerMother::between(0, 2147483647),
				self::WITHOUT_PARENT_CATEGORY,
				self::DESCRIPTION_IS_AVAILABLE,
				self::POSITION_IS_AVAILABLE);
		}
		
		public static function randomWithParentCategory(Category $category): Category
		{
			return self::create(
				Uuid::random(),
				WordMother::random(),
				IntegerMother::between(0, 2147483647),
				$category,
				self::DESCRIPTION_IS_AVAILABLE,
				self::POSITION_IS_AVAILABLE
			
			);
		}
	}
