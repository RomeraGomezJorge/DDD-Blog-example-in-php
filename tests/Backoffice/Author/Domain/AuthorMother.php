<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Author\Domain;
	
	use App\Backoffice\Author\Domain\Author;
	use App\Backoffice\Author\Domain\AuthorFullnameIsAvailableSpecification;
	use App\Backoffice\Author\Domain\ValueObject\AuthorBiography;
	use App\Backoffice\Author\Domain\ValueObject\AuthorFullname;
	use App\Shared\Domain\SlugGenerator;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Shared\Domain\WordMother;
	use DateTime;
	use PHPUnit\Framework\TestCase;
	
	final class AuthorMother extends testCase
	{
		public static function random(): Author
		{
			return self::create(Uuid::random(), WordMother::random(), WordMother::random(), true);
		}
		
		public static function create(
			Uuid $id,
			string $fullname,
			?string $biography,
			bool $isFullnameInUse
		): Author {
			$uniqueAuthorDescriptionSpecificationStub = (new AuthorMother)->uniqueAuthorFullnameSpecificationStub();
			
			$uniqueAuthorDescriptionSpecificationStub->method('isSatisfiedBy')->willReturn($isFullnameInUse);
			
			$slugGeneratorStub = (new AuthorMother())->slugGeneratorStub();
			
			$slugGeneratorStub->method('generate');
			
			return Author::create(
				$id,
				new AuthorFullname($fullname),
				new AuthorBiography($biography),
				new DateTime(),
				$uniqueAuthorDescriptionSpecificationStub,
				$slugGeneratorStub
			);
		}
		
		public function uniqueAuthorFullnameSpecificationStub()
		{
			return $this->createMock(AuthorFullnameIsAvailableSpecification::class);
		}
		
		public function slugGeneratorStub()
		{
			return $this->createMock(SlugGenerator::class);
		}
		
		public static function randomWithFullname($fullname): Author
		{
			return self::create(Uuid::random(), $fullname, WordMother::random(), true);
		}
	}
