<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Domain;
	
	use App\Shared\Domain\SlugGenerator;
	use DateTimeInterface;
	use App\Backoffice\Category\Domain\Exception\UnavailableCategoryDescription;
	use App\Backoffice\Category\Domain\Exception\UnavailableCategoryPosition;
	use App\Backoffice\Category\Domain\ValueObject\CategoryDescription;
	use App\Backoffice\Category\Domain\ValueObject\CategoryPosition;
	use App\Shared\Domain\Aggregate\AggregateRoot;
	use App\Shared\Domain\ValueObject\Uuid;
	use DateTime;
	
	class Category extends AggregateRoot
	{
		private string $id;
		private string $description;
		private int $position;
		private ?Category $parent;
		private $children;
		private Datetime $createAt;
		private string $slug;
		

		public static function create(
			Uuid $id,
			CategoryDescription $description,
			CategoryPosition $position,
			?Category $parent,
			DateTimeInterface $createAt,
			CategoryDescriptionIsAvailableSpecification $categoryDescriptionIsAvailableSpecification,
			CategoryPositionIsAvailableSpecification $categoryPositionIsAvailableSpecification,
			SlugGenerator $slugGenerator
		): self {
			$category = new self();
			$category->id = $id->value();
			$category->description = $description->value();
			$category->position = $position->value();
			$category->createAt = $createAt;
			$category->parent = $parent;
			$category->slug = $slugGenerator->generate($description->value());
			
			if (!$categoryDescriptionIsAvailableSpecification->isSatisfiedBy($category)) {
				throw new UnavailableCategoryDescription($description);
			}
			
			if (!$categoryPositionIsAvailableSpecification->isSatisfiedBy($category)) {
				throw new UnavailableCategoryPosition($position);
			}
			
			$category->recordThat(CategoryWasCreated::with($id->value(), $description->value(),
				$position->__toString()));
			
			return $category;
		}
		
		public function changeDetails(
			CategoryDescription $aNewDescription,
			CategoryPosition $aNewPosition,
			?Category $aNewParent,
			CategoryDescriptionIsAvailableSpecification $categoryDescriptionIsAvailableSpecification,
			CategoryPositionIsAvailableSpecification $categoryPositionIsAvailableSpecification,
			SlugGenerator $slugGenerator
		):self
		{
			$this->changeDescription($aNewDescription, $categoryDescriptionIsAvailableSpecification);
			$this->changePosition($aNewPosition, $categoryPositionIsAvailableSpecification);
			$this->parent = $aNewParent;
			$this->slug = $slugGenerator->generate($this->description);
			return $this;
		}
		
		private function changeDescription(
			CategoryDescription $aNewDescription,
			CategoryDescriptionIsAvailableSpecification $categoryDescriptionIsAvailableSpecification
		): void {
			if ($aNewDescription->isEqual($this->description)) {
				return;
			}
			
			$this->description = $aNewDescription->value();
			
			if (!$categoryDescriptionIsAvailableSpecification->isSatisfiedBy($this)) {
				throw new UnavailableCategoryDescription($aNewDescription);
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
		
		public function description(): string
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
