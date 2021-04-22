<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Infrastructure\Bus\Command;
	
	use App\Shared\Domain\Bus\Command\Command;
	use App\Shared\Infrastructure\Bus\Command\CommandNotRegisteredError;
	use App\Shared\Infrastructure\Bus\Command\InMemorySymfonyCommandBus;
	use App\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
	use RuntimeException;
	
	final class InMemorySymfonyCommandBusTest extends UnitTestCase
	{
		private $commandBus;
		
		/** @test */
		public function it_should_be_able_to_handle_a_command(): void
		{
			$this->expectException(RuntimeException::class);
			
			$this->commandBus->dispatch(new FakeCommand());
		}
		
		/** @test */
		public function it_should_raise_an_exception_dispatching_a_non_registered_command(): void
		{
			$this->expectException(CommandNotRegisteredError::class);
			
			$this->commandBus->dispatch($this->mock(Command::class));
		}
		
		protected function setUp(): void
		{
			parent::setUp();
			
			$this->commandBus = new InMemorySymfonyCommandBus([$this->commandHandler()]);
		}
		
		private function commandHandler()
		{
			return new class
			{
				public function __invoke(FakeCommand $command)
				{
					throw new RuntimeException('This works fine!');
				}
			};
		}
	}
