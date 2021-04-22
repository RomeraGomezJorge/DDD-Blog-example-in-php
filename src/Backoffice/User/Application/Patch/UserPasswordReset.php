<?php
	
	declare(strict_types=1);
	
	namespace App\Backoffice\User\Application\Patch;
	
	use App\Backoffice\User\Application\Get\Single\UserFinder;
	use App\Backoffice\User\Domain\PasswordEncoder;
	use App\Backoffice\User\Domain\UserRepository;
	use App\Backoffice\User\Domain\ValueObject\UserPassword;
	use DateTime;
	
	final class UserPasswordReset
	{
		private UserRepository $repository;
		private UserFinder  $finder;
		private PasswordEncoder $passwordEncoder;
		
		public function __construct(UserRepository $repository, PasswordEncoder $passwordEncoder)
		{
			$this->repository = $repository;
			$this->finder = new UserFinder($repository);
			$this->passwordEncoder = $passwordEncoder;
		}
		
		public function __invoke(string $id, string $plainPassword)
		{
			$plainPassword = new UserPassword($plainPassword);
			
			$user = $this->finder->__invoke($id);
			
			$encodedPassword = $this->passwordEncoder->encode($user, $plainPassword->value());
			
			$user->encodePassword($encodedPassword);
			
			$user->setUpdateAt(new DateTime());
			
			$this->repository->save($user);
		}
	}