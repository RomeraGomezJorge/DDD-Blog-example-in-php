<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Domain;
	
	use App\Shared\Domain\Bus\Event\DomainEvent;
	
	final class ArticleWasCreated extends DomainEvent
	{
		private string $entry;
		private string $title;
		private string $excerpt;
		private string $body;
		private string $isPublished;
		private string $authorId;
		private string $categoryId;
		private string $slug;
		
		private function __construct(
			string $id,
			string $entry,
			string $title,
			string $excerpt,
			string $body,
			string $isPublished,
			string $authorId,
			string $categoryId,
			string $slug,
			string $eventId = null,
			string $occurredOn = null
		) {
			parent::__construct($id, $eventId, $occurredOn);
			$this->entry = $entry;
			$this->title = $title;
			$this->excerpt = $excerpt;
			$this->body = $body;
			$this->isPublished = $isPublished;
			$this->authorId = $authorId;
			$this->categoryId = $categoryId;
			$this->slug = $slug;
		}
		
		public static function with(
			string $id,
			string $entry,
			string $title,
			string $excerpt,
			string $body,
			string $isPublished,
			string $authorId,
			string $categoryId,
			string $slug
		): self {
			return new self($id, $entry, $title, $excerpt, $body, $isPublished, $authorId, $categoryId,$slug);
		}
		
		public static function eventName(): string
		{
			return 'article.was.created';
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
		
		public function isPublished(): string
		{
			return $this->isPublished;
		}
		
		public function authorId(): string
		{
			return $this->authorId;
		}
		
		public function categoryId(): string
		{
			return $this->categoryId;
		}
		
		public function slug():string
		{
			return $this->slug;
		}
	}
