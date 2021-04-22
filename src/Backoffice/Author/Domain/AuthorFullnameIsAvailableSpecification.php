<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Domain;
	
	class AuthorFullnameIsAvailableSpecification
	{
		private AuthorRepository $repository;
		
		public function __construct(AuthorRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function isSatisfiedBy(Author $author): bool
		{
			return $this->repository->isAvailable(array('fullname' => $author->fullname()));
		}
	}