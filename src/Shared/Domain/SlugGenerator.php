<?php
	
	namespace App\Shared\Domain;
	
	interface SlugGenerator
	{
		public function generate(string $string): string;
	}
