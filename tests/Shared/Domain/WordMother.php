<?php
	
	declare(strict_types=1);
	
	namespace App\Tests\Shared\Domain;
	
	final class WordMother
	{
		public static function random($wordToReturn = 2): string
		{
			return MotherCreator::random()->words($wordToReturn, $asText = true);
		}
	}
