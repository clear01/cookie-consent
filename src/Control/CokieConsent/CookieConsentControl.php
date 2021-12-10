<?php
declare(strict_types = 1);

namespace Clear01\CookieConsent\Control\CokieConsent;

use Nette\Application\UI\Control;

class CookieConsentControl extends Control
{
	/** @var array */
	private $config;


	public function __construct(array $config)
	{
		parent::__construct();
		$this->config = $config;
	}


	public function setConfig(string $key, $value): void
	{
		$this->config[$key] = $value;
	}


	public function render()
	{
		$this->template->config = $this->config;
		$this->template->setFile(__DIR__ . '/template.latte');
		$this->template->render();
	}
}