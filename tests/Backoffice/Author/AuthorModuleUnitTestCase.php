<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\Author;
	
	use App\Backoffice\Author\Domain\Author;
	use App\Backoffice\Author\Domain\AuthorFullnameIsAvailableSpecification;
	use App\Backoffice\Author\Domain\AuthorRepository;
	use App\Shared\Domain\Bus\Event\EventBus;
	use App\Shared\Domain\SlugGenerator;
	use App\Shared\Domain\ValueObject\Uuid;
	use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
	
	abstract class AuthorModuleUnitTestCase extends UnitTestCase
	{
		const FULLNAME_IS_AVAILABLE = true;
		const FULLNAME_IS_NOT_AVAILABLE = false;
		const AUTHOR_NOT_FOUND = null;
		protected $repository;
		protected $authorFullnameIsAvailableSpecification;
		protected $bus;
		private $slugGenerator;
		
		public function shouldHaveUniqueAuthorFullname(Author $author): void
		{
			$this->authorFullnameIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->once()
				->with($this->similarTo($author))
				->andReturn(self::FULLNAME_IS_AVAILABLE);
		}
		
		protected function authorFullnameIsAvailableSpecification(): AuthorFullnameIsAvailableSpecification
		{
			return $this->authorFullnameIsAvailableSpecification = $this->authorFullnameIsAvailableSpecification ?: $this->mock(AuthorFullnameIsAvailableSpecification::class);
		}
		
		protected function slugGenerator(): SlugGenerator
		{
			return $this->slugGenerator = $this->slugGenerator ?: $this->mock(SlugGenerator::class);
		}
		
		public function shouldNotHaveUniqueAuthorFullname(Author $author): void
		{
			$this->authorFullnameIsAvailableSpecification()
				->shouldReceive('isSatisfiedBy')
				->once()
				->with($this->similarTo($author))
				->andReturn(self::FULLNAME_IS_NOT_AVAILABLE);
		}
		
		protected function shouldFind(Author $author): void
		{
			$id = new Uuid($author->id());
			$this->repository()
				->shouldReceive('search')
				->once()
				->with($this->similarTo($id))
				->andReturn($author);
		}
		
		protected function repository(): AuthorRepository
		{
			return $this->repository = $this->repository ?: $this->mock(AuthorRepository::class);
		}
		
		protected function shouldNotFind($id): void
		{
			$id = new Uuid($id);
			
			$this->repository()
				->shouldReceive('search')
				->once()
				->with($this->similarTo($id))
				->andReturn(self::AUTHOR_NOT_FOUND);
		}
		
		protected function shouldSave(Author $Author): void
		{
			$this->repository()
				->shouldReceive('save')
				->once()
				->with($this->similarTo($Author))
				->andReturnNull();
		}
		
		protected function shouldNotSave()
		{
			$this->repository()
				->shouldReceive('save')
				->never();
		}
		
		protected function shouldNotPublish()
		{
			$this->bus()
				->shouldReceive('publish')
				->never();
		}
		
		protected function bus(): EventBus
		{
			return $this->bus = $this->bus ?: $this->mock(EventBus::class);
		}
		
		protected function shouldBeAvailable(array $criteria): void
		{
			$this->repository()
				->shouldReceive('isAvailable')
				->once()
				->with($criteria)
				->andReturn(self::FULLNAME_IS_AVAILABLE);
		}
		
		protected function shouldBeUnavailable(array $criteria): void
		{
			$this->repository()
				->shouldReceive('isAvailable')
				->once()
				->with($criteria)
				->andReturn(self::FULLNAME_IS_NOT_AVAILABLE);
		}
		
		protected function shouldGenerateSlug(string $fullname):void
		{
			$this->slugGenerator()
				->shouldReceive('generate')
				->once()
				->with($fullname);
		}
	}
