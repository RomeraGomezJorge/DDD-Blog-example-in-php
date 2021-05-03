<?php
	declare(strict_types=1);
	
	namespace App\WebSite\Infrastructure\UserInterface\Web;
	
	use App\Shared\Infrastructure\RelatedEntities;
	use App\Shared\Infrastructure\Symfony\WebController;
	
	final class WebsiteMenuGetController extends WebController
	{
		public function __invoke( RelatedEntities $relatedEntities)
		{
			return $this->render(TwigTemplateConstants::MENU_FILE_PATH,
				['categories' => $relatedEntities->getCategoriesSortedByPosition()]);
		}
	}