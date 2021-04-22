<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Application\Get\Single;
	
	use App\Backoffice\Author\Domain\AuthorRepository;
	
	final class CheckAuthorFullnameAvailability
	{
		private AuthorRepository $authorRepository;
		
		public function __construct(AuthorRepository $authorRepository)
		{
			$this->authorRepository = $authorRepository;
		}
		
		public function __invoke(string $fullname): bool
		{
			return $this->authorRepository->isAvailable(['fullname' => $fullname]);
		}
	}