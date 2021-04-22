<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Domain;
	
	use App\Shared\Domain\Bus\Event\DomainEvent;
	
	final class ArticleDetailsWasChanged extends DomainEvent
	{
		private string $id;
		private string $aNewEntry;
		private string $aNewTitle;
		private string $aNewExcerpt;
		private string $aNewBody;
		private string $aNewStatus;
		private string $aNewAuthorId;
		private string $aNewCategoryId;
		private string $slug;
		
		private function __construct(
			string $id,
			string $aNewEntry,
			string $aNewTitle,
			string $aNewExcerpt,
			string $aNewBody,
			string $aNewStatus,
			string $aNewAuthorId,
			string $aNewCategoryId,
			string $slug,
			string $eventId = null,
			string $occurredOn = null
		) {
			parent::__construct($id, $eventId, $occurredOn);
			$this->id = $id;
			$this->aNewEntry = $aNewEntry;
			$this->aNewTitle = $aNewTitle;
			$this->aNewExcerpt = $aNewExcerpt;
			$this->aNewBody = $aNewBody;
			$this->aNewStatus = $aNewStatus;
			$this->aNewAuthorId = $aNewAuthorId;
			$this->aNewCategoryId = $aNewCategoryId;
			$this->slug = $slug;
		}
		
		public static function with(
			string $id,
			string $aNewEntry,
			string $aNewTitle,
			string $aNewExcerpt,
			string $aNewBody,
			string $aNewStatus,
			string $aNewAuthorId,
			string $aNewCategoryId,
			string $slug
		): self {
			return new self($id, $aNewEntry, $aNewTitle, $aNewExcerpt, $aNewBody, $aNewStatus, $aNewAuthorId,$aNewCategoryId,$slug);
		}
		
		public static function eventName(): string
		{
			return 'article.details.was.changed';
		}
		
		public function id(): string
		{
			return $this->id;
		}
		
		public function entry(): string
		{
			return $this->aNewEntry;
		}
		
		public function title(): string
		{
			return $this->aNewTitle;
		}
		
		public function excerpt(): string
		{
			return $this->aNewExcerpt;
		}
		
		public function body(): string
		{
			return $this->aNewBody;
		}
		
		public function state(): string
		{
			return $this->aNewStatus;
		}
		
		public function authorId(): string
		{
			return $this->aNewAuthorId;
		}
		
		public function categoryId(): string
		{
			return $this->aNewCategoryId;
		}
		
		
		public function slug(): string
		{
			return $this->slug;
		}
		
		
	}
