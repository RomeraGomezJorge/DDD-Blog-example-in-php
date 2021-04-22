<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Author\Infrastructure\UserInterface\Web;
	
	use Symfony\Component\Validator\Constraints as Assert;
	use Symfony\Component\Validator\ConstraintViolationListInterface;
	use Symfony\Component\Validator\Validation;
	
	final class ValidationRulesToCreateAndUpdate
	{
		public static function verify($request): ConstraintViolationListInterface
		{
			$constraint = new Assert\Collection(
				[
					'id' => new Assert\Uuid(),
					'fullname' => [new Assert\NotBlank(), new Assert\Length(['min' => 3, 'max' => 100])],
					'biography' => new Assert\Optional(new Assert\Length(['max' => 255])),
					'csrf_token' => new Assert\NotBlank()
				]
			);
			
			$input = $request->request->all();
			
			return Validation::createValidator()->validate($input, $constraint);
		}
	}