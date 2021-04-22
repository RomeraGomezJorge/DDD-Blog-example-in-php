<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Application\Post;
	
	use App\Backoffice\Article\Application\Shared\AttachmentUtils;
	use App\Backoffice\Article\Domain\Article;
	use App\Backoffice\Article\Domain\ArticleRepository;
	use App\Backoffice\Article\Domain\ArticleTitleIsAvailableSpecification;
	use App\Backoffice\Article\Domain\ValueObject\ArticleEntry;
	use App\Backoffice\Article\Domain\ValueObject\ArticleExcerpt;
	use App\Backoffice\Article\Domain\ValueObject\ArticleState;
	use App\Backoffice\Article\Domain\ValueObject\ArticleTitle;
	use App\Backoffice\Author\Application\Get\Single\AuthorFinder;
	use App\Backoffice\Author\Domain\AuthorRepository;
	use App\Backoffice\Category\Application\Get\Single\CategoryFinder;
	use App\Backoffice\Category\Domain\CategoryRepository;
	use App\Shared\Domain\Bus\Event\EventBus;
	use App\Shared\Domain\SlugGenerator;
	use App\Shared\Domain\ValueObject\Uuid;
	use DateTime;
	
	final class ArticleCreator
	{
		const URL_IS_NOT_DEFINE = '';
		private ArticleRepository   $repository;
		private AuthorFinder    $finderAuthor;
		private CategoryFinder  $finderCategory;
		private AttachmentUtils $attachmentUtils;
		private ArticleTitleIsAvailableSpecification $articleTitleIsAvailableSpecification;
		private EventBus    $bus;
		private SlugGenerator $slugGenerator;
		
		public function __construct(
			ArticleRepository $repository,
			AuthorRepository $authorRepository,
			CategoryRepository $categoryRepository,
			AttachmentUtils $attachmentUtils,
			ArticleTitleIsAvailableSpecification $articleTitleIsAvailableSpecification,
			SlugGenerator $slugGenerator,
			EventBus $bus
		) {
			$this->repository = $repository;
			$this->finderAuthor = new AuthorFinder($authorRepository);
			$this->finderCategory = new CategoryFinder($categoryRepository);
			$this->attachmentUtils = $attachmentUtils;
			$this->articleTitleIsAvailableSpecification = $articleTitleIsAvailableSpecification;
			$this->bus = $bus;
			$this->slugGenerator = $slugGenerator;
		}
		
		public function __invoke(
			string $id,
			string $entry,
			string $title,
			string $excerpt,
			?string $body,
			string $state,
			string $authorId,
			string $categoryId,
			array $attachments,
			array $youtubeVideos
		) {
			$author = $this->finderAuthor->__invoke($authorId);
			
			$category = $this->finderCategory->__invoke($categoryId);
			
			$createAt = new DateTime();
			
			$article = Article::create(
				new Uuid($id),
				new ArticleEntry($entry),
				new ArticleTitle($title),
				new ArticleExcerpt($excerpt),
				trim($body),
				new ArticleState($state),
				$author,
				$category,
				$createAt,
				$this->articleTitleIsAvailableSpecification,
				$this->slugGenerator
			);
			
			$this->attachmentUtils->attachFiles($attachments, $article);
			
			$this->attachmentUtils->attachVideos($youtubeVideos, $article);
			
			$this->repository->save($article);
			
			$this->bus->publish(...$article->pullDomainEvents());
		}
	}