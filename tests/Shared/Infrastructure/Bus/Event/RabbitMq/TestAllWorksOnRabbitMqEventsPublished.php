<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Infrastructure\Bus\Event\RabbitMq;
	
	use App\Mooc\Courses\Domain\CourseCreatedDomainEvent;
	use App\Mooc\CoursesCounter\Domain\CoursesCounterIncrementedDomainEvent;
	use App\Shared\Domain\Bus\Event\DomainEventSubscriber;
	
	final class TestAllWorksOnRabbitMqEventsPublished implements DomainEventSubscriber
	{
		public static function subscribedTo(): array
		{
			return [
				CourseCreatedDomainEvent::class,
				CoursesCounterIncrementedDomainEvent::class,
			];
		}
		
		/** @param CourseCreatedDomainEvent|CoursesCounterIncrementedDomainEvent $event */
		public function __invoke($event)
		{
		}
	}
