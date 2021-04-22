<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Domain;
	
	use App\Backoffice\Article\Domain\Attachment\Attachment;
	use App\Backoffice\Article\Domain\Exception\NonUniqueArticleTitle;
	use App\Backoffice\Article\Domain\ValueObject\ArticleEntry;
	use App\Backoffice\Article\Domain\ValueObject\ArticleExcerpt;
	use App\Backoffice\Article\Domain\ValueObject\ArticleState;
	use App\Backoffice\Article\Domain\ValueObject\ArticleTitle;
	use App\Backoffice\Author\Domain\Author;
	use App\Backoffice\Category\Domain\Category;
	use App\Shared\Domain\Aggregate\AggregateRoot;
	use App\Shared\Domain\SlugGenerator;
	use App\Shared\Domain\ValueObject\Uuid;
	use DateTime;
	use DateTimeInterface;
	use Doctrine\Common\Collections\ArrayCollection;
	
	
	class Article extends AggregateRoot
	{
		private string $id;
		private string $entry;
		private string $title;
		private string $excerpt;
		private ?string $body;
		private string $state;
		private Author $author;
		private Category $category;
		private DateTime $createAt;
		private DateTime $updateAt;
		private $attachments;
		private string $slug;
		
		public static function create(
			Uuid $id,
			ArticleEntry $entry,
			ArticleTitle $title,
			ArticleExcerpt $excerpt,
			?string $body,
			ArticleState $state,
			Author $author,
			Category $category,
			DateTimeInterface $createAt,
			ArticleTitleIsAvailableSpecification $articleTitleIsAvailableSpecification,
			SlugGenerator $slugGenerator
		): self {
			$article = new self();
			$article->id = $id->value();
			$article->entry = $entry->value();
			$article->title = $title->value();
			$article->excerpt = $excerpt->value();
			$article->body = $body;
			$article->state = $state->value();
			$article->author = $author;
			$article->category = $category;
			$article->createAt = $createAt;
			$article->updateAt = $createAt;
			$article->slug = $slugGenerator->generate($title->value());
			
			if (!$articleTitleIsAvailableSpecification->isSatisfiedBy($article)) {
				throw new NonUniqueArticleTitle($title);
			}
			
			$article->recordThat(ArticleWasCreated::with(
				$id->value(),
				$entry->value(),
				$title->value(),
				$excerpt->value(),
				$body, $state->value(),
				$author->id(),
				$category->id(),
				$article->slug
			));
			
			return $article;
		}
		
		public function changeDetails(
			ArticleEntry $aNewEntry,
			ArticleTitle $aNewTitle,
			ArticleExcerpt $aNewExcerpt,
			?string $aNewBody,
			ArticleState $state,
			Author $aNewAuthor,
			Category $aNewCategory,
			DateTimeInterface $updateAt,
			ArticleTitleIsAvailableSpecification $articleTitleIsAvailableSpecification,
			SlugGenerator $slugGenerator
		): self {
			$this->changeTitle($aNewTitle, $articleTitleIsAvailableSpecification);
			$this->entry = $aNewEntry->value();
			$this->excerpt = $aNewExcerpt->value();
			$this->body = $aNewBody;
			$this->state = $state->value();
			$this->author = $aNewAuthor;
			$this->category = $aNewCategory;
			$this->updateAt = $updateAt;
			$this->slug = $slugGenerator->generate($this->title);
			
			$this->recordThat(
				ArticleDetailsWasChanged::with(
					$this->id,
					$aNewEntry->value(),
					$aNewTitle->value(),
					$aNewExcerpt->value(),
					$aNewBody,
					$state->value(),
					$aNewAuthor->id(),
					$aNewCategory->id(),
					$this->slug)
			
			);
			
			return $this;
		}
		
		private function changeTitle(
			ArticleTitle $aNewTitle,
			ArticleTitleIsAvailableSpecification $articleTitleIsAvailableSpecification
		): void {
			if ($aNewTitle->isEqual($this->title)) {
				return;
			}
			
			$this->title = $aNewTitle->value();
			
			if (!$articleTitleIsAvailableSpecification->isSatisfiedBy($this)) {
				throw new NonUniqueArticleTitle($aNewTitle);
			}
		}
		
		public function getAttachments(): ?array
		{
			return $this->attachments;
		}
		
		public function addAttachment(Attachment $attachment)
		{
			$this->attachments[] = $attachment->toArray();
			
			return $this;
		}
		
		public function removeAttachment(Attachment $attachment): self
		{
			$attachments = new ArrayCollection($this->attachments);
			
			if ($attachments->contains($attachment->toArray())) {
				$attachments->removeElement($attachment->toArray());
			}
			
			$this->attachments = $attachments->toArray();
			
			return $this;
		}
		
		public function id(): string
		{
			return $this->id;
		}
		
		public function entry(): string
		{
			return $this->entry;
		}
		
		public function title(): string
		{
			return $this->title;
		}
		
		public function excerpt(): string
		{
			return $this->excerpt;
		}
		
		public function body(): string
		{
			return $this->body;
		}
		
		public function state(): string
		{
			return $this->state;
		}
		
		public function author(): Author
		{
			return $this->author;
		}
		
		public function category(): Category
		{
			return $this->category;
		}
		
		public function createAt(): DateTime
		{
			return $this->createAt;
		}
		
		public function updateAt(): DateTime
		{
			return $this->updateAt;
		}
	}
