<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Domain;
	
	final class EmailMother
	{
		public static function random(): string
		{
			return MotherCreator::random()->email;
		}
	}
