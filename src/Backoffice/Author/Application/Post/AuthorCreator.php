<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Application\Post;
	
	use App\Backoffice\Author\Domain\Author;
	use App\Backoffice\Author\Domain\AuthorFullnameIsAvailableSpecification;
	use App\Backoffice\Author\Domain\AuthorRepository;
	use App\Backoffice\Author\Domain\ValueObject\AuthorBiography;
	use App\Backoffice\Author\Domain\ValueObject\AuthorFullname;
	use App\Shared\Domain\Bus\Event\EventBus;
	use App\Shared\Domain\SlugGenerator;
	use App\Shared\Domain\ValueObject\Uuid;
	use DateTime;
	
	
	final class AuthorCreator
	{
		private AuthorRepository $repository;
		private AuthorFullnameIsAvailableSpecification $authorFullnameIsAvailableSpecification;
		private EventBus $bus;
		private SlugGenerator $slugGenerator;
		
		public function __construct(
			AuthorRepository $repository,
			AuthorFullnameIsAvailableSpecification $authorFullnameIsAvailableSpecification,
			SlugGenerator $slugGenerator,
			EventBus $bus
		) {
			$this->repository = $repository;
			$this->authorFullnameIsAvailableSpecification = $authorFullnameIsAvailableSpecification;
			$this->bus = $bus;
			$this->slugGenerator = $slugGenerator;
		}
		
		public function __invoke(
			string $id,
			string $fullname,
			?string $biography
		) {
			$id = new Uuid($id);
			
			$createAt = new DateTime();
			
			$author = Author::create(
				$id,
				new AuthorFullname($fullname),
				new AuthorBiography($biography),
				$createAt,
				$this->authorFullnameIsAvailableSpecification,
				$this->slugGenerator
				);
			
			$this->repository->save($author);
			
			$this->bus->publish(...$author->pullDomainEvents());
		}
	}