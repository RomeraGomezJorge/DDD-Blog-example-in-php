<?php
	
	declare(strict_types=1);
	
	namespace App\WebSite\Infrastructure\UserInterface\Web\Home;
	
	use App\Backoffice\Article\Domain\ArticleRepository;
	use App\Backoffice\Article\Infrastructure\Storage\LocalAttachmentManager;
	use App\Shared\Infrastructure\RelatedEntities;
	use App\Shared\Infrastructure\Symfony\WebController;
	use App\WebSite\Application\Get\Collection\LastArticleFromEachCategoryFinder;
	use App\WebSite\Infrastructure\UserInterface\Web\TwigTemplateConstants;
	use Symfony\Component\HttpFoundation\Response;
	
	class WebsiteLastArticleFromEachCategoryGetController extends WebController
	{
		public function __invoke(LastArticleFromEachCategoryFinder $finder, RelatedEntities $relatedEntities ): Response
		{
			$finder->__invoke();
			
			
			return $this->render(TwigTemplateConstants::LAST_ARTICLE_FROM_EACH_CATEGORY_FILE_PATH, [
				'articles' => $finder->__invoke(),
				'categories' => $relatedEntities->getAllParentCategoriesSortedByPosition(),
				'medium_image_extension' =>  LocalAttachmentManager::MEDIUM_IMAGE['extension'],
				'small_image_extension' =>  LocalAttachmentManager::SMALL_IMAGE['extension']
			]);
		}
	}
	