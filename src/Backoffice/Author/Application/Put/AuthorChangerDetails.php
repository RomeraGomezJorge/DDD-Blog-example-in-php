<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Application\Put;
	
	use App\Backoffice\Author\Application\Get\Single\AuthorFinder;
	use App\Backoffice\Author\Domain\AuthorFullnameIsAvailableSpecification;
	use App\Backoffice\Author\Domain\AuthorRepository;
	use App\Backoffice\Author\Domain\ValueObject\AuthorBiography;
	use App\Backoffice\Author\Domain\ValueObject\AuthorFullname;
	use App\Shared\Domain\SlugGenerator;
	
	final class AuthorChangerDetails
	{
		private AuthorRepository $repository;
		private AuthorFinder  $finder;
		private AuthorFullnameIsAvailableSpecification $authorFullnameIsAvailableSpecification;
		private SlugGenerator $slugGenerator;
		
		public function __construct(
			AuthorRepository $repository,
			AuthorFullnameIsAvailableSpecification $authorFullnameIsAvailableSpecification,
			SlugGenerator $slugGenerator
		) {
			$this->repository = $repository;
			$this->finder = new AuthorFinder($repository);
			$this->authorFullnameIsAvailableSpecification = $authorFullnameIsAvailableSpecification;
			$this->slugGenerator = $slugGenerator;
		}
		
		public function __invoke(string $id, string $newFullname, ?string $newBiography): void
		{
			$authorFound = $this->finder->__invoke($id);
			
			$authorFound->changeDetails(
				new AuthorFullname($newFullname), new AuthorBiography($newBiography),
				$this->authorFullnameIsAvailableSpecification,
				$this->slugGenerator
				);
			
			$this->repository->save($authorFound);
		}
	}