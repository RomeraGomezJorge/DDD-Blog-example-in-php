<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Application\Put;
	
	use App\Backoffice\Article\Application\Get\Single\ArticleFinder;
	use App\Backoffice\Article\Application\Shared\AttachmentUtils;
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
	use DateTime;
	
	final class ArticleChangeDetails
	{
		private ArticleRepository $repository;
		private ArticleFinder $finder;
		private AuthorFinder $finderAuthor;
		private CategoryFinder $finderCategory;
		private AttachmentUtils $attachmentUtils;
		private ArticleTitleIsAvailableSpecification $articleTitleIsAvailableSpecification;
		private EventBus $bus;
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
			$this->finder = new ArticleFinder($repository);
			$this->finderAuthor = new AuthorFinder($authorRepository);
			$this->finderCategory = new CategoryFinder($categoryRepository);
			$this->attachmentUtils = $attachmentUtils;
			$this->articleTitleIsAvailableSpecification = $articleTitleIsAvailableSpecification;
			$this->bus = $bus;
			$this->slugGenerator = $slugGenerator;
		}
		
		public function __invoke(
			string $id,
			string $aNewEntry,
			string $aNewTitle,
			string $aNewExcerpt,
			?string $aNewBody,
			string $state,
			string $newAuthorId,
			string $newCategoryId,
			array $attachments,
			array $youtubeVideos
		): void {
			$articleFound = $this->finder->__invoke($id);
			$aNewAuthor = $this->finderAuthor->__invoke($newAuthorId);
			$aNewCategory = $this->finderCategory->__invoke($newCategoryId);
			
			$articleFound->changeDetails(
				new ArticleEntry($aNewEntry),
				new ArticleTitle($aNewTitle),
				new ArticleExcerpt($aNewExcerpt),
				trim($aNewBody),
				new ArticleState($state),
				$aNewAuthor,
				$aNewCategory,
				new DateTime(),
				$this->articleTitleIsAvailableSpecification,
				$this->slugGenerator
			);
			
			$this->attachmentUtils->attachFiles($attachments, $articleFound);
			
			$this->attachmentUtils->attachVideos($youtubeVideos, $articleFound);
			
			$this->repository->save($articleFound);
			
			$this->bus->publish(...$articleFound->pullDomainEvents());
		}
	}