<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Domain;
	
	use App\Shared\Domain\Criteria\Criteria;
	use App\Shared\Domain\ValueObject\Uuid;
	
	interface AuthorRepository
	{
		public function save(Author $Author): void;
		
		public function search(Uuid $id): ?Author;
		
		public function searchAll(): array;
		
		public function matching(Criteria $criteria): array;
		
		public function totalMatchingRows(Criteria $criteria): int;
		
		public function delete(Author $Author): void;
		
		public function isAvailable(array $criteria): bool;
	}