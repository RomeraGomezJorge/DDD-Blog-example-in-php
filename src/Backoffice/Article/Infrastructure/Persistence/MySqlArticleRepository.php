<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Infrastructure\Persistence;
	
	use App\Backoffice\Article\Domain\Article;
	use App\Backoffice\Article\Domain\ArticleRepository;
	use App\Backoffice\Category\Domain\Category;
	use App\Shared\Domain\Criteria\Criteria;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
	use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
	
	final class MySqlArticleRepository extends DoctrineRepository implements ArticleRepository
	{
		const NOT_SETTING_VALUE = null;
		const ENTITY_CLASS = Article::class;
		const DESCRIPTION_IS_NOT_IN_USE = false;
		const DESCRIPTION_HAS_ALREADY_BEEN_CREATED_FOR_THIS_VEHICLE_MAKER_NAME = true;
		const NOT_FOUND = null;
		private ?int $totalMatchingRows = null;
		
		public function save(Article $district): void
		{
			$this->persist($district);
		}
		
		public function search(Uuid $id): ?Article
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
		
		public function matching(Criteria $criteria, ?Category $categoryFound): array
		{
			$matching = $this->getMatchingFrom($criteria, $categoryFound);
			
			$this->totalMatchingRows = $matching->count();
			
			return $matching->toArray();
		}
		
		private function getMatchingFrom(Criteria $criteria, $categoryFound): Collection
		{
			$doctrineCriteria = $this->isFoundAddAsCriteria($categoryFound, $criteria);
			
			return $this->repository(self::ENTITY_CLASS)->matching($doctrineCriteria);
		}
		
		private function isFoundAddAsCriteria(?Category $categoryFound, Criteria $criteria): DoctrineCriteria
		{
			$doctrineCriteria = DoctrineCriteriaConverter::convert($criteria);
			
			if ($categoryFound === self::NOT_FOUND) {
				return $doctrineCriteria;
			}
			
			return $doctrineCriteria->andWhere(
				DoctrineCriteria::expr()->eq('category', $categoryFound)
			);
		}
		
		public function totalMatchingRows(Criteria $criteria, ?Category $category): int
		{
			if ($this->totalMatchingRows === self::NOT_SETTING_VALUE) {
				return $this->getMatchingFrom($criteria, $category)->count();
			}
			
			return $this->totalMatchingRows;
		}
		
		public function delete(Article $district): void
		{
			$this->remove($district);
		}
	}
	
	