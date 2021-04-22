<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Infrastructure\Behat;
	
	use App\Shared\Infrastructure\Bus\Event\DomainEventJsonDeserializer;
	use App\Shared\Infrastructure\Bus\Event\InMemory\InMemorySymfonyEventBus;
	use App\Shared\Infrastructure\Doctrine\DatabaseConnections;
	use Behat\Behat\Context\Context;
	use Behat\Gherkin\Node\PyStringNode;
	
	final class ApplicationFeatureContext implements Context
	{
		private $connections;
		private $bus;
		private $deserializer;
		
		public function __construct(
			DatabaseConnections $connections,
			InMemorySymfonyEventBus $bus,
			DomainEventJsonDeserializer $deserializer
		) {
			$this->connections = $connections;
			$this->bus = $bus;
			$this->deserializer = $deserializer;
		}
		
		/** @BeforeScenario */
		public function cleanEnvironment(): void
		{
			$this->connections->clear();
			$this->connections->truncate();
		}
		
		/**
		 * @Given /^I send an event to the event bus:$/
		 */
		public function iSendAnEventToTheEventBus(PyStringNode $event)
		{
			$domainEvent = $this->deserializer->deserialize($event->getRaw());
			
			$this->bus->publish($domainEvent);
		}
	}
