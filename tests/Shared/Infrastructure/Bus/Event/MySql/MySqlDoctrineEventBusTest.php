<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Infrastructure\Bus\Event\MySql;
	
	use App\Apps\Mooc\Backend\MoocBackendKernel;
	use App\Shared\Domain\Bus\Event\DomainEvent;
	use App\Shared\Infrastructure\Bus\Event\DomainEventMapping;
	use App\Shared\Infrastructure\Bus\Event\MySql\MySqlDoctrineDomainEventsConsumer;
	use App\Shared\Infrastructure\Bus\Event\MySql\MySqlDoctrineEventBus;
	use App\Tests\Mooc\Courses\Domain\CourseCreatedDomainEventMother;
	use App\Tests\Mooc\CoursesCounter\Domain\CoursesCounterIncrementedDomainEventMother;
	use App\Tests\Shared\Infrastructure\PhpUnit\InfrastructureTestCase;
	use Doctrine\ORM\EntityManager;
	
	final class MySqlDoctrineEventBusTest extends InfrastructureTestCase
	{
		private $bus;
		private $consumer;
		
		/** @test */
		public function it_should_publish_and_consume_domain_events_from_msql(): void
		{
			$domainEvent = CourseCreatedDomainEventMother::random();
			$anotherDomainEvent = CoursesCounterIncrementedDomainEventMother::random();
			
			$this->bus->publish($domainEvent, $anotherDomainEvent);
			
			$this->consumer->consume(
				fn (DomainEvent ...$expectedEvents) => $this->assertContainsEquals($domainEvent, $expectedEvents),
				$eventsToConsume = 2
			);
		}
		
		protected function setUp(): void
		{
			parent::setUp();
			
			$this->bus = new MySqlDoctrineEventBus($this->service(EntityManager::class));
			$this->consumer = new MySqlDoctrineDomainEventsConsumer(
				$this->service(EntityManager::class),
				$this->service(DomainEventMapping::class)
			);
		}
		
		protected function kernelClass(): string
		{
			return MoocBackendKernel::class;
		}
	}
