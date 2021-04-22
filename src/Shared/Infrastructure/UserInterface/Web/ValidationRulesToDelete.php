<?php
	
	namespace App\Shared\Infrastructure\UserInterface\Web;
	
	use Symfony\Component\Validator\Constraints as Assert;
	use Symfony\Component\Validator\ConstraintViolationListInterface;
	use Symfony\Component\Validator\Validation;
	
	final class ValidationRulesToDelete
	{
		public static function verify($request): ConstraintViolationListInterface
		{
			$constraint = new Assert\Collection(
				[
					'id' => new Assert\Uuid(),
					'csrf_token' => new Assert\NotBlank(),
					'css_selector_to_handle_tr_style_that_contains_items_to_delete' => new Assert\NotBlank()
				]
			);
			
			$input = $request->request->all();
			
			return Validation::createValidator()->validate($input, $constraint);
		}
	}