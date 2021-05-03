<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Domain;
	
	use App\Backoffice\Category\Domain\ValueObject\CategoryDescription;
	use App\Shared\Domain\SlugGenerator;
	use DateTimeInterface;
	use App\Backoffice\Category\Domain\Exception\UnavailableCategoryName;
	use App\Backoffice\Category\Domain\Exception\UnavailableCategoryPosition;
	use App\Backoffice\Category\Domain\ValueObject\CategoryName;
	use App\Backoffice\Category\Domain\ValueObject\CategoryPosition;
	use App\Shared\Domain\Aggregate\AggregateRoot;
	use App\Shared\Domain\ValueObject\Uuid;
	use DateTime;
	
	class Category extends AggregateRoot
	{
		private string $id;
		private string $name;
		private ?string $description;
		private int $position;
		private ?Category $parent;
		private $children;
		private Datetime $createAt;
		private string $slug;
		

		public static function create(
			Uuid $id,
			CategoryName $name,
			CategoryDescription $description,
			CategoryPosition $position,
			?Category $parent,
			DateTimeInterface $createAt,
			CategoryNameIsAvailableSpecification $categoryNameIsAvailableSpecification,
			CategoryPositionIsAvailableSpecification $categoryPositionIsAvailableSpecification,
			SlugGenerator $slugGenerator
		): self {
			$category = new self();
			$category->id = $id->value();
			$category->name = $name->value();
			$category->description = $description->value();
			$category->position = $position->value();
			$category->createAt = $createAt;
			$category->parent = $parent;
			$category->slug = $slugGenerator->generate($name->value());
			
			if (!$categoryNameIsAvailableSpecification->isSatisfiedBy($category)) {
				throw new UnavailableCategoryName($name);
			}
			
			if (!$categoryPositionIsAvailableSpecification->isSatisfiedBy($category)) {
				throw new UnavailableCategoryPosition($position);
			}
			
			$category->recordThat(CategoryWasCreated::with($id->value(), $name->value(),
				$position->__toString()));
			
			return $category;
		}
		
		public function changeDetails(
			CategoryName $aNewName,
			CategoryDescription $aDescription,
			CategoryPosition $aNewPosition,
			?Category $aNewParent,
			CategoryNameIsAvailableSpecification $categoryNameIsAvailableSpecification,
			CategoryPositionIsAvailableSpecification $categoryPositionIsAvailableSpecification,
			SlugGenerator $slugGenerator
		):self
		{
			$this->changeName($aNewName, $categoryNameIsAvailableSpecification);
			$this->changePosition($aNewPosition, $categoryPositionIsAvailableSpecification);
			$this->description = $aDescription->value();
			$this->parent = $aNewParent;
			$this->slug = $slugGenerator->generate($this->name);
			return $this;
		}
		
		private function changeName(
			CategoryName $aNewName,
			CategoryNameIsAvailableSpecification $categoryNameIsAvailableSpecification
		): void {
			if ($aNewName->isEqual($this->name)) {
				return;
			}
			
			$this->name = $aNewName->value();
			
			if (!$categoryNameIsAvailableSpecification->isSatisfiedBy($this)) {
				throw new UnavailableCategoryName($aNewName);
			}
		}
		
		private function changePosition(
			CategoryPosition $aNewPosition,
			CategoryPositionIsAvailableSpecification $categoryPositionIsAvailableSpecification
		): void {
			if ($aNewPosition->isEqual($this->position)) {
				return;
			}
			
			$this->position = $aNewPosition->value();
			
			if (!$categoryPositionIsAvailableSpecification->isSatisfiedBy($this)) {
				throw new UnavailableCategoryPosition($aNewPosition);
			}
		}
		
		public function id(): string
		{
			return $this->id;
		}
		
		public function name(): string
		{
			return $this->name;
		}
		
		public function description(): ?string
		{
			return $this->description;
		}
		
		public function position(): int
		{
			return $this->position;
		}
		
		public function setId(int $id): void
		{
			$this->id = $id;
		}
		
		public function parent(): ?Category
		{
			return $this->parent;
		}
		
		public function children(): array
		{
			return $this->children->toArray();
		}
	}
