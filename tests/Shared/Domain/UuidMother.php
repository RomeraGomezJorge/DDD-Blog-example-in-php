<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Domain;
	
	final class UuidMother
	{
		public static function random(): string
		{
			return MotherCreator::random()->unique()->uuid;
		}
		
		public static function invalid(): string
		{
			return 'invalid_uuid';
		}
	}
