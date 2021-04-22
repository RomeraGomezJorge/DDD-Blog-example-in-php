<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Backoffice\User\Application\Delete;
	
	use App\Backoffice\User\Application\Delete\UserDeleter;
	use App\Backoffice\User\Domain\Exception\UserNotExist;
	use App\Backoffice\User\Domain\User;
	use App\Tests\Backoffice\User\Domain\UserMother;
	use App\Tests\Backoffice\User\UserModuleUnitTestCase;
	
	final class UserDeleteTest extends UserModuleUnitTestCase
	{
		private UserDeleter $deleter;
		private User $user;
		
		/** @test */
		public function it_should_delete_an_existing_user(): void
		{
			$this->shouldFind($this->user);
			
			$this->repository()
				->shouldReceive('delete')
				->once()
				->with($this->similarTo($this->user));
			
			$this->deleter->__invoke($this->user->getId());
		}
		
		/** @test */
		public function it_should_throw_an_exception_when_the_author_does_not_exit(): void
		{
			$this->expectException(UserNotExist::class);
			
			$this->shouldNotFind($this->user->getId());
			
			$this->repository()
				->shouldReceive('delete')
				->never();
			
			$this->deleter->__invoke($this->user->getId());
		}
		
		protected function setUp(): void
		{
			parent::setUp(); // TODO: Change the autogenerated stub
			
			$this->deleter = new UserDeleter($this->repository());
			
			$this->user = UserMother::random();
		}
	}