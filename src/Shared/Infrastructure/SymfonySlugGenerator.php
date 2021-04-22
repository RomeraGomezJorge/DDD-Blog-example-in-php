<?php
	
	declare(strict_types=1);
	
	namespace App\Shared\Infrastructure;
	
	use App\Shared\Domain\SlugGenerator;
	use Symfony\Component\String\Slugger\SluggerInterface;
	
	final class SymfonySlugGenerator implements SlugGenerator
	{
		private SluggerInterface $slugger;
		
		public function __construct(SluggerInterface $slugger)
		{
			$this->slugger = $slugger;
		}
		
		public function generate(string $string): string
		{
			return (string)$this->slugger->slug(strtolower($string));
		}
	}
