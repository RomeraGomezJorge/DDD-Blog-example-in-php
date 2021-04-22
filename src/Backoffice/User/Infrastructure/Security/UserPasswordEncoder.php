<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Infrastructure\Security;
	
	use App\Backoffice\User\Domain\PasswordEncoder;
	use App\Backoffice\User\Domain\User;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	
	final class UserPasswordEncoder implements PasswordEncoder
	{
		private UserPasswordEncoderInterface $encoder;
		
		public function __construct(UserPasswordEncoderInterface $encoder)
		{
			$this->encoder = $encoder;
		}
		
		public function encode(User $user, string $plainPassword): string
		{
			return $this->encoder->encodePassword($user, $plainPassword);
		}
	}