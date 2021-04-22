<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Domain\Exception;
	
	use App\Backoffice\Article\Domain\ValueObject\ArticleState;
	use App\Backoffice\Article\Domain\ValueObject\ArticleTitle;
	use App\Shared\Domain\DomainError;
	
	final class NonUniqueArticleTitle extends DomainError
	{
		private string $title;
		
		public function __construct(ArticleTitle $title)
		{
			$this->title = $title->value();
			
			parent::__construct();
		}
		
		public function errorCode(): string
		{
			return 'article_title_already_exists';
		}
		
		protected function errorMessage(): string
		{
			return sprintf('La publicación con el titulo "%s" que ha ingresado ya está registrado.',
				$this->title);
		}
	}