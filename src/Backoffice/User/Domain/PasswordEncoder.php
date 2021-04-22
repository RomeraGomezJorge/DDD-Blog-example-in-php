<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Domain;
	
	interface PasswordEncoder
	{
		public function encode(User $user, string $plainPassword): string;
	}