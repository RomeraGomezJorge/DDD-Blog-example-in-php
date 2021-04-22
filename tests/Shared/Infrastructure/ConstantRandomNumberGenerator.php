<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Infrastructure;
	
	use App\Shared\Domain\RandomNumberGenerator;
	
	final class ConstantRandomNumberGenerator implements RandomNumberGenerator
	{
		public function generate(): int
		{
			return 1;
		}
	}
