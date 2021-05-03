<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Category;
	
	use App\Shared\Infrastructure\Constant\FormConstant;
	use App\Shared\Infrastructure\RamseyUuidGenerator;
	use App\Shared\Infrastructure\RenderFormInterface;
	use App\Shared\Infrastructure\Symfony\FlashSession;
	use App\Shared\Infrastructure\Symfony\WebController;
	
	final class FormToCreateACategoryByAjax extends WebController implements RenderFormInterface
	{
		private RamseyUuidGenerator $ramseyUuidGenerator;
		private FlashSession $flashSession;
		
		public function __construct(RamseyUuidGenerator $ramseyUuidGenerator, FlashSession $flashSession)
		{
			$this->ramseyUuidGenerator = $ramseyUuidGenerator;
			$this->flashSession = $flashSession;
		}
		
		public function __invoke(): ?string
		{
			return $this->render(TwigTemplateConstants::ADD_CATEGORY_BY_MODAL_FILE_PATH, [
				'form_action_attribute' => TwigTemplateConstants::CREATE_BY_AJAX_PATH,
				'id' => $this->ramseyUuidGenerator->generate(),
				'name' => $this->flashSession->get('inputs.name'),
				'description' => $this->flashSession->get('inputs.description'),
				'position' => $this->flashSession->get('inputs.position'),
				'name_available_path' => TwigTemplateConstants::NAME_AVAILABLE_PATH,
				'position_available_path' => TwigTemplateConstants::POSITION_AVAILABLE_PATH,
				'submit_button_label' => FormConstant::SUBMIT_BUTTON_VALUE_TO_CREATE,
			])->getContent();
		}
	}