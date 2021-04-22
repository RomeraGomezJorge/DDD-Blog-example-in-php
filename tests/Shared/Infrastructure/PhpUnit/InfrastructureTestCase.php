<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Infrastructure\PhpUnit;
	
	use App\Tests\Shared\Domain\TestUtils;
	use Doctrine\ORM\EntityManager;
	use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
	use Throwable;

//abstract class InfrastructureTestCase extends KernelTestCase
	abstract class InfrastructureTestCase extends WebTestCase
	{
		protected function setUp(): void
		{
			$_SERVER['KERNEL_CLASS'] = $this->kernelClass();
			
			self::bootKernel(['environment' => 'test']);
			
			parent::setUp();
		}
		
		abstract protected function kernelClass();
		
		protected function assertSimilar($expected, $actual): void
		{
			TestUtils::assertSimilar($expected, $actual);
		}
		
		/** @return mixed */
		protected function parameter($parameter)
		{
			return self::$container->getParameter($parameter);
		}
		
		protected function clearUnitOfWork(): void
		{
			$this->service(EntityManager::class)->clear();
		}
		
		/** @return mixed */
		protected function service($id)
		{
			if ($id !== EntityManager::class) {
				return self::$container->get($id);
			}
			
			$kernel = self::bootKernel();
			
			return $kernel->getContainer()
				->get('doctrine')
				->getManager();
		}
		
		protected function eventually(
			callable $fn,
			$totalRetries = 3,
			$timeToWaitOnErrorInSeconds = 1,
			$attempt = 0
		): void {
			try {
				$fn();
			} catch (Throwable $error) {
				if ($totalRetries === $attempt) {
					throw $error;
				}
				
				sleep($timeToWaitOnErrorInSeconds);
				
				$this->eventually($fn, $totalRetries, $timeToWaitOnErrorInSeconds, $attempt + 1);
			}
		}
	}
