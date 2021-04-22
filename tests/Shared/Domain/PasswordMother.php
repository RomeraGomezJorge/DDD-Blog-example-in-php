<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Domain;
	
	final class PasswordMother
	{
		const UPPERCASE_CHARACTER = 'C';
		const NUMBER_CHARACTER = '1';
		
		public static function random(): string
		{
			return MotherCreator::random()->password(8, 12) . self::UPPERCASE_CHARACTER . self::NUMBER_CHARACTER;
		}
	}
