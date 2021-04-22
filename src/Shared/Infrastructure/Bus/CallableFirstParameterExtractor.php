<?php
	
	declare(strict_types=1);
	
	namespace App\Shared\Infrastructure\Bus;
	
	use App\Shared\Domain\Bus\Event\DomainEventSubscriber;
	use ReflectionClass;
	use ReflectionMethod;
	use function Lambdish\Phunctional\map;
	use function Lambdish\Phunctional\reduce;
	use function Lambdish\Phunctional\reindex;
	
	final class CallableFirstParameterExtractor
	{
		public static function forCallables(iterable $callables): array
		{
			return map(self::unflatten(), reindex(self::classExtractor(new self()), $callables));
		}
		
		private static function unflatten(): callable
		{
			return static fn ($value) => [$value];
		}
		
		private static function classExtractor(CallableFirstParameterExtractor $parameterExtractor): callable
		{
			return static fn (callable $handler): string => $parameterExtractor->extract($handler);
		}
		
		public static function forPipedCallables(iterable $callables): array
		{
			return reduce(self::pipedCallablesReducer(), $callables, []);
		}
		
		private static function pipedCallablesReducer(): callable
		{
			return static function ($subscribers, DomainEventSubscriber $subscriber): array {
				$subscribedEvents = $subscriber::subscribedTo();
				
				foreach ($subscribedEvents as $subscribedEvent) {
					$subscribers[$subscribedEvent][] = $subscriber;
				}
				
				return $subscribers;
			};
		}
		
		public function extract($class): ?string
		{
			$reflector = new ReflectionClass($class);
			$method = $reflector->getMethod('__invoke');
			
			if ($this->hasOnlyOneParameter($method)) {
				return $this->firstParameterClassFrom($method);
			}
			
			return null;
		}
		
		private function hasOnlyOneParameter(ReflectionMethod $method): bool
		{
			return $method->getNumberOfParameters() === 1;
		}
		
		private function firstParameterClassFrom(ReflectionMethod $method): string
		{
			return $method->getParameters()[0]->getClass()->getName();
		}
	}
