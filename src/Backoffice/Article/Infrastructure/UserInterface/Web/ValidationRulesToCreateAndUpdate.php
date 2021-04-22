<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\Article\Infrastructure\UserInterface\Web;
	
	use App\Shared\Infrastructure\UserInterface\Web\ValidationRulesToCreateFile;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Validator\Constraints as Assert;
	use Symfony\Component\Validator\ConstraintViolationListInterface;
	use Symfony\Component\Validator\Validation;
	
	final class ValidationRulesToCreateAndUpdate
	{
		const DRAFT_STATE = "0";
		const PUBLIC_STATE = "1";
		const VALID_STATUSES = [self::PUBLIC_STATE, self::DRAFT_STATE];
		
		public static function verify(Request $request): ConstraintViolationListInterface
		{
			$validationResultsToCreateOrUpdate = (new ValidationRulesToCreateAndUpdate)->validationResultsToCreateOrUpdate($request);
			
			$validationResultsForImage = ValidationRulesToCreateFile::verify($request);
			
			$validationResultsToCreateOrUpdate->addAll($validationResultsForImage);
			
			return $validationResultsToCreateOrUpdate;
		}
		
		private function validationResultsToCreateOrUpdate(Request $request): ConstraintViolationListInterface
		{
			$constraint = new Assert\Collection([
				'id' => new Assert\Uuid(),
				'entry' => new Assert\Optional(),
				'title' => new Assert\NotBlank(),
				'excerpt' => new Assert\NotBlank(),
				'body' => new Assert\Optional(),
				'state' => new Assert\Choice(self::VALID_STATUSES),
				'csrf_token' => new Assert\NotBlank(),
				'author_id' => new Assert\Uuid(),
				'category_id' => new Assert\Uuid(),
				'attachment' => new Assert\Optional([
					new Assert\Type('array'),
					new Assert\All([
						new Assert\Collection([
							'title' => new Assert\Optional(new Assert\Length(['max' => 100])),
						]),
					]),
				]),
				'youtube_video' => new Assert\Optional([
					new Assert\Type('array'),
					new Assert\All([
						new Assert\Collection([
							'title' => new Assert\Optional(new Assert\Length(['max' => 100])),
							'url' => new Assert\Optional(new Assert\Length(['max' => 100]))
						]),
					]),
				]),
			
			]);
			
			$input = $request->request->all();
			
			return Validation::createValidator()->validate($input, $constraint);
		}
	}