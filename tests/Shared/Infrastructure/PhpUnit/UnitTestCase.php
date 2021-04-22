<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Infrastructure\PhpUnit;
	
	use App\Tests\Shared\Domain\TestUtils;
	use Mockery;
	use Mockery\Adapter\Phpunit\MockeryTestCase;
	use Mockery\Matcher\MatcherAbstract;
	use Mockery\MockInterface;
	
	abstract class UnitTestCase extends MockeryTestCase
	{
		protected function mock(string $className): MockInterface
		{
			return Mockery::mock($className);
		}
		
		protected function isSimilar($expected, $actual): bool
		{
			return TestUtils::isSimilar($expected, $actual);
		}
		
		protected function assertSimilar($expected, $actual): void
		{
			TestUtils::assertSimilar($expected, $actual);
		}
		
		protected function similarTo($value, $delta = 0.0): MatcherAbstract
		{
			return TestUtils::similarTo($value, $delta);
		}
	}
