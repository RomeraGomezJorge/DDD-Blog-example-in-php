<?php
	
	declare(strict_types=1);
	
	namespace App\WebSite\Infrastructure\Persistence;
	
	use App\Backoffice\Article\Domain\Article;
	use App\Backoffice\Article\Domain\ArticleRepository;
	use App\Backoffice\Category\Domain\Category;
	use App\Shared\Domain\Criteria\Criteria;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineCriteriaConverter;
	use App\Shared\Infrastructure\Persistence\Doctrine\DoctrineRepository;
	use App\WebSite\Domain\WebsiteRepository;
	use DateTime;
	use Doctrine\Common\Collections\Collection;
	use Doctrine\Common\Collections\Criteria as DoctrineCriteria;
	use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
	
	final class MySqlWebsiteRepository extends DoctrineRepository implements WebsiteRepository
	{
		
		const ENTITY_CLASS = Article::class;
		const NUMBER_OF_ARTICLE_FROM_EACH_CATEGORY = 2;
		const PUBLIC = 1;
		const MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';
		
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
		
		public function searchPreviousArticle(Article $article ):?array
		{
			$query = '
				SELECT
					title,
				    slug
				FROM
				    article
				WHERE
				    id <> "'.$article->id().'" AND
				    create_at < "'.$article->createAt()->format(self::MYSQL_DATETIME_FORMAT).'" AND
				    category_id = "'.$article->category()->id().'" AND
				    state = 1
				ORDER BY
				    create_at
				DESC
				LIMIT 1
			';

			$conn = $this->entityManager()->getConnection();

			$stmt = $conn->prepare($query);

			$stmt->execute();

			return $stmt->fetchAssociative();

		}
		
		public function searchNextArticle(Article $article ):?array
		{
			$query = '
				SELECT
				    title,
				    slug
				FROM
				    article
				WHERE
				    id <> "'.$article->id().'" AND
				    create_at > "'.$article->createAt()->format(self::MYSQL_DATETIME_FORMAT).'" AND
				    category_id = "'.$article->category()->id().'" AND
				    state = 1
				ORDER BY
				    create_at
				ASC
				LIMIT 1
			';

			$conn = $this->entityManager()->getConnection();

			$stmt = $conn->prepare($query);

			$stmt->execute();

			return $stmt->fetchAssociative();

		}
	}
	
	