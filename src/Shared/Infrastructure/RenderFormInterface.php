<?php
	
	namespace App\Shared\Infrastructure;
	
	Interface RenderFormInterface
	{
		public function __invoke(): ?string;
	}