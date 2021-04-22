<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Infrastructure\PhpUnit;
	
	
	use App\Tests\Shared\Infrastructure\Arranger\EnvironmentArranger;
	use App\Tests\Shared\Infrastructure\Doctrine\MySqlDatabaseCleaner;
	use Doctrine\ORM\EntityManager;
	use Doctrine\ORM\EntityManagerInterface;
	use function Lambdish\Phunctional\apply;
	
	final class BlogEnvironmentArranger implements EnvironmentArranger
	{
		private EntityManager $entityManager;
		
		public function __construct(EntityManagerInterface $entityManager)
		{
			$this->entityManager = $entityManager;
		}
		
		public function arrange(): void
		{
			apply(new MySqlDatabaseCleaner(), [$this->entityManager]);
		}
		
		public function close(): void
		{
		}
	}
