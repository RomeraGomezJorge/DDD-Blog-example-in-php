<?php
	
	declare(strict_types=1);
	
	namespace App\Shared\Infrastructure\Symfony;
	
	use Symfony\Component\HttpFoundation\RedirectResponse;
	use Symfony\Component\HttpFoundation\Session\SessionInterface;
	use Symfony\Component\HttpKernel\Event\ExceptionEvent;
	use Symfony\Component\Routing\RouterInterface;
	use Throwable;
	
	
	final class ExceptionListener
	{
		private RouterInterface $router;
		private SessionInterface $session;
		
		public function __construct(RouterInterface $router, SessionInterface $session)
		{
			$this->router = $router;
			$this->session = $session;
		}
		
		public function onException(ExceptionEvent $event): void
		{
			$exception = $event->getThrowable();
			
			$event->setResponse(
				$this->redirectWithErrors($exception)
			);
		}
		
		private function redirectWithErrors(Throwable $exception): RedirectResponse
		{
			$this->session->getFlashBag()->set('message', $exception->getMessage());
			
			return new RedirectResponse($this->router->generate('error_page'));
		}
	}
