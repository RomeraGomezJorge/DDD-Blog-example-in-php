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
	use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
	
	final class MySqlArticleRepository extends DoctrineRepository implements ArticleRepository
	{
		const NOT_SETTING_VALUE = null;
		const ENTITY_CLASS = Article::class;
		const NOT_FOUND = null;
		const NUMBER_OF_ARTICLE_FROM_EACH_CATEGORY = 2;
		const PUBLIC = 1;
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
		
		public function findLastedTenArticleFromEachCategory(): ?array
		{
			$query = 'SELECT
			    art.category_id,
			    art.create_at,
			    art.title,
			    art.excerpt,
			    art.attachments,
			    art.slug,
			    au.fullname as author_fullname
			FROM
			    article art
			    INNER JOIN
			    author au
			    ON
			    art.author_id = au.id
			WHERE
			    art.state = 1 AND (
			    SELECT
			        COUNT(*)
			    FROM
			        article
			    WHERE
			        art.state = ' . self::PUBLIC . ' AND art.category_id = category_id AND art.id <= id
		     
			) <= ' . self::NUMBER_OF_ARTICLE_FROM_EACH_CATEGORY . '
			ORDER BY
				art.create_at DESC
				';
			
			
			$conn = $this->entityManager()->getConnection();
			
			$stmt = $conn->prepare($query);
			
			$stmt->execute();
			
			return $stmt->fetchAllAssociative();
		}
		
		public function searchBySlug(string $slug): ?Article
		{
			return $this->repository(self::ENTITY_CLASS)->findOneBy(['slug' => $slug]);
		}
		
		public function searchPreviousArticle(Uuid $id ,Uuid $category_id)
		{
			$query = '
				SELECT
					title,
				    slug
				FROM
				    article art
				WHERE
				    art.id <> "c6fcc6f0-7eb7-4dff-965f-ac4aeddf86df" AND
				    art.category_id = "59e4f48a-6df7-4399-a68e-46a4c44465ca" AND
				    art.state = 1 AND
				    create_at < "2021-04-22 21:07:04"
				ORDER BY
				    create_at
				DESC
				LIMIT 1
			';

			$conn = $this->entityManager()->getConnection();

			$stmt = $conn->prepare($query);

			$stmt->execute();

			return $stmt->fetchAllAssociative();

		}
		
		public function searchNExtArticle(Uuid $id ,Uuid $category_id)
		{
			$query = '
				SELECT
				    title,
				    slug
				FROM
				    article art
				WHERE
				    art.id <> "c6fcc6f0-7eb7-4dff-965f-ac4aeddf86df" AND art.category_id = "59e4f48a-6df7-4399-a68e-46a4c44465ca" AND art.state = 1 AND create_at > "2021-04-22 21:07:04"
				ORDER BY
				    create_at
				ASC
				LIMIT 1
			';

			$conn = $this->entityManager()->getConnection();

			$stmt = $conn->prepare($query);

			$stmt->execute();

			return $stmt->fetchAllAssociative();

		}
	}
	
	