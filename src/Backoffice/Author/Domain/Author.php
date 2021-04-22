<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Domain;
	
	use App\Backoffice\Author\Domain\Exception\UnavailableAuthorFullname;
	use App\Backoffice\Author\Domain\ValueObject\AuthorBiography;
	use App\Backoffice\Author\Domain\ValueObject\AuthorFullname;
	use App\Shared\Domain\Aggregate\AggregateRoot;
	use App\Shared\Domain\SlugGenerator;
	use App\Shared\Domain\ValueObject\Uuid;
	use DateTime;
	use DateTimeInterface;
	
	class Author extends AggregateRoot
	{
		private string $id;
		private string $fullname;
		private string $biography;
		private string $slug;
		private Datetime $createAt;
		

		public static function create(
			Uuid $id,
			AuthorFullname $fullname,
			AuthorBiography $biography,
			DateTimeInterface $createAt,
			AuthorFullnameIsAvailableSpecification $authorFullnameIsAvailableSpecification,
			SlugGenerator $slugGenerator
		): self {
			$author = new self();
			$author->id = $id->value();
			$author->fullname = $fullname->value();
			$author->biography = $biography->value();
			$author->slug = $slugGenerator->generate($fullname->value());
			$author->createAt = $createAt;
			$author->recordThat(AuthorWasCreated::with($id->value(), $fullname->value(), $biography->value()));
			
			if (!$authorFullnameIsAvailableSpecification->isSatisfiedBy($author)) {
				throw new UnavailableAuthorFullname($fullname);
			}
			return $author;
		}
		
		public function changeDetails(
			AuthorFullname $aNewFullname,
			AuthorBiography $aNewBiography,
			AuthorFullnameIsAvailableSpecification $authorFullnameIsAvailableSpecification,
			SlugGenerator $slugGenerator
		): self {
			$this->changeFullname($aNewFullname, $authorFullnameIsAvailableSpecification);
			$this->biography = $aNewBiography->value();
			$this->slug = $slugGenerator->generate($this->fullname);
			return $this;
		}
		
		private function changeFullname(
			AuthorFullname $aNewFullname,
			AuthorFullnameIsAvailableSpecification $authorFullnameIsAvailableSpecification
		): void {
			if ($aNewFullname->isEqual($this->fullname)) {
				return;
			}
			
			$this->fullname = $aNewFullname->value();
			
			if (!$authorFullnameIsAvailableSpecification->isSatisfiedBy($this)) {
				throw new UnavailableAuthorFullname($aNewFullname);
			}
		}
		
		public function id(): String
		{
			return $this->id;
		}
		
		public function fullname(): string
		{
			return $this->fullname;
		}
		
		public function biography(): string
		{
			return $this->biography;
		}
		
		public function slug():string
		{
			return $this->slug;
		}
		
		public function createAt(): DateTime
		{
			return $this->createAt;
		}
	}
