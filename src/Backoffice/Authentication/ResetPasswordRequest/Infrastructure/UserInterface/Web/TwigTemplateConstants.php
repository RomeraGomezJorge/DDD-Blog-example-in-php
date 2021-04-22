<?php
	
	
	namespace App\Backoffice\Authentication\ResetPasswordRequest\Infrastructure\UserInterface\Web;
	
	
	final class TwigTemplateConstants
	{
		const FORGOT_PASSWORD_REQUEST_PATH = 'forgot_password_request';
		const RESET_PASSWORD_PATH = 'reset_password';
		const EMAIL_MESSAGE_SENT_PATH = 'email_message_sent';
		const EMAIL_MESSAGE_SENT_FILE_PATH = 'backoffice/authentication/resetPassword/emailMessageSent.html.twig';
		const FORM_TO_HANDLE_RESET_PASSWORD_REQUEST_FILE_PATH = 'backoffice/authentication/resetPassword/formToHandleResetPasswordRequest.html.twig';
		const FORM_TO_HANDLE_NEW_PASSWORD_FILE_PATH = 'backoffice/authentication/resetPassword/formToHandleNewPassword.html.twig';
		const PASSWORD_RESET_EMAIL_TEMPLATE = 'backoffice/authentication/resetPassword/passwordResetEmailTemplate.html.twig';
		const EMAIL_MESSAGE_SENT_IMAGE = 'https://i.imgur.com/YM0Gnoy.png';
	}