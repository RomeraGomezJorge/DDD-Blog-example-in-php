<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Infrastructure\Bus\Query;
	
	use App\Shared\Domain\Bus\Query\Response;
	
	final class FakeResponse implements Response
	{
		private int $number;
		
		public function __construct(int $number)
		{
			$this->number = $number;
		}
		
		public function number(): int
		{
			return $this->number;
		}
	}
