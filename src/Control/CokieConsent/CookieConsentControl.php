<?php
declare(strict_types = 1);

namespace Clear01\CookieConsent\Control\CokieConsent;

use HttpRequest;
use Nette\Application\UI\Control;
use Nette\Diagnostics\Debugger;
use Nette\Http\Request;
use Nette\Utils\Json;
use Throwable;

class CookieConsentControl extends Control
{
	/** @var array */
	private $config;

	/** @var Request */
	private $request;


	public function __construct(array $config, Request $request)
	{
		parent::__construct();
		$this->config = $config;
		$this->request = $request;
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

	public function renderDefault()
	{
		$cookies = $this->request->getCookie('cookie_consent_level');

		try {
			$consents = $cookies ? Json::decode($cookies, Json::FORCE_ARRAY) : [];
		} catch (Throwable $e) {
			$consents = [];
		}

		$this->template->consents = $consents;
		$this->template->setFile(__DIR__ . '/default.latte');
		$this->template->render();
	}
}