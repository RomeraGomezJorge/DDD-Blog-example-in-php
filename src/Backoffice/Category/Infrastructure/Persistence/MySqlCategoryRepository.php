<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\Persistence;
	
	use App\Backoffice\Category\Domain\Category;
	use App\Backoffice\Category\Domain\CategoryRepository;
	use App\Shared\Domain\Criteria\Criteria;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
	use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
	
	final class MySqlCategoryRepository extends DoctrineRepository implements CategoryRepository
	{
		const NOT_SETTING_VALUE = null;
		const ENTITY_CLASS = Category::class;
		const NOT_FOUND = null;
		private ?int $totalMatchingRows = null;
		
		public function save(Category $Category): void
		{
			$this->persist($Category);
		}
		
		public function search(Uuid $id): ?Category
		{
			return $this->repository(self::ENTITY_CLASS)->find($id);
		}
		
		public function searchAll(): array
		{
			return $this->repository(self::ENTITY_CLASS)->findAll();
		}
		
		public function isAvailable(array $criteria): bool
		{
			return !((bool)$this->repository(self::ENTITY_CLASS)->findOneBy($criteria));
		}
		
		public function matching(Criteria $criteria, ?Category $parentFound): array
		{
			$matching = $this->getMatchingFrom($criteria, $parentFound);
			
			$this->totalMatchingRows = $matching->count();
			
			return $matching->toArray();
		}
		
		private function getMatchingFrom(Criteria $criteria, $parentFound): Collection
		{
			$doctrineCriteria = $this->isFoundAddAsCriteria($parentFound, $criteria);
			
			return $this->repository(self::ENTITY_CLASS)->matching($doctrineCriteria);
		}
		
		private function isFoundAddAsCriteria(?Category $parentFound, Criteria $criteria): DoctrineCriteria
		{
			$doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);
			
			if ($parentFound === self::NOT_FOUND) {
				return $doctrineCriteria;
			}
			
			return $doctrineCriteria->andWhere(
				DoctrineCriteria::expr()->eq('parent', $parentFound)
			);
		}
		
		public function totalMatchingRows(Criteria $criteria, ?Category $parent): int
		{
			if ($this->totalMatchingRows === self::NOT_SETTING_VALUE) {
				return $this->getMatchingFrom($criteria, $parent)->count();
			}
			
			return $this->totalMatchingRows;
		}
		
		public function delete(Category $Category): void
		{
			$this->remove($Category);
		}
	}