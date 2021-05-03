<?php
	
	declare(strict_types=1);
	
	namespace App\WebSite\Infrastructure\UserInterface\Web\Home;
	
	use App\Shared\Infrastructure\Symfony\WebController;
	use App\WebSite\Infrastructure\UserInterface\Web\TwigTemplateConstants;
	use Symfony\Component\HttpFoundation\Response;
	
	class WebsiteHomeGetController extends WebController
	{
		public function __invoke(): Response
		{
			return $this->render(TwigTemplateConstants::HOME_MAIN_BASE_FILE_PATH);
		}
	}
	