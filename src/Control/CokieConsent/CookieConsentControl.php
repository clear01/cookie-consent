<?php
declare(strict_types = 1);

namespace Clear01\CookieConsent\Control\CokieConsent;

use DateTime;
use Nette\Application\UI\Control;
use Nette\Http\Request;
use Nette\Http\Response;
use Nette\Utils\Json;
use Throwable;

class CookieConsentControl extends Control
{
	/** @var array */
	private $config;

	/** @var Request */
	private $request;

	/** @var Response */
	private $response;


	public function __construct(array $config, Request $request, Response $response)
	{
		parent::__construct();
		$this->config = $config;
		$this->request = $request;
		$this->response = $response;
		$this->onAnchor[] = function () {
			$consentReload = $this->request->getCookie('cookie_consent_reload');
			if ($consentReload) {
				$reloadAt = new DateTime($consentReload);
				if ($reloadAt < new DateTime()) {
					$this->response->deleteCookie("cookie_consent_level");
					$this->response->deleteCookie("cookie_consent_user_accepted");
					$this->response->deleteCookie("cookie_consent_user_consent_token");
					$this->response->deleteCookie("cookie_consent_reload");
				}
			}
		};
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