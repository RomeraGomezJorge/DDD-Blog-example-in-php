<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Application\Get\Single;
	
	use App\Backoffice\Author\Domain\Author;
	use App\Backoffice\Author\Domain\AuthorRepository;
	use App\Backoffice\Author\Domain\Exception\AuthorNotExist;
	use App\Shared\Domain\ValueObject\Uuid;
	
	final class AuthorFinder
	{
		const NOT_FOUND = null;
		private AuthorRepository $repository;
		
		public function __construct(AuthorRepository $repository)
		{
			$this->repository = $repository;
		}
		
		public function __invoke(string $id): Author
		{
			$id = new Uuid($id);
			
			$Author = $this->repository->search($id);
			
			if (self::NOT_FOUND === $Author) {
				throw new AuthorNotExist(($id)->value());
			}
			
			return $Author;
		}
	}