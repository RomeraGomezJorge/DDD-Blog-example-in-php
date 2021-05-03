<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Category\Infrastructure\UserInterface\Web\Category;
	
	use Symfony\Component\Validator\Constraints as Assert;
	use Symfony\Component\Validator\ConstraintViolationListInterface;
	use Symfony\Component\Validator\Validation;
	
	final class ValidationRulesToCreateAndUpdate
	{
		public static function verify($request): ConstraintViolationListInterface
		{
			$constraint = new Assert\Collection(
				[
					'id' => [new Assert\Uuid()],
					'name' => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 255])],
					'description' => [new Assert\Length(['min' => 0, 'max' => 255])],
					'position' => [new Assert\NotBlank(), new Assert\GreaterThan(0)],
					'csrf_token' => [new Assert\NotBlank()]
				]
			);
			
			$input = $request->request->all();
			
			return Validation::createValidator()->validate($input, $constraint);
		}
	}