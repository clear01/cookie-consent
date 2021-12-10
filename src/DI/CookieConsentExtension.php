<?php
declare(strict_types = 1);

namespace Clear01\CookieConsent\DI;

use Clear01\CookieConsent\Control\CokieConsent\ICookieConsentControlFactory;
use Nette\DI\CompilerExtension;

class CookieConsentExtension extends CompilerExtension
{
	/** @var array|object */
	protected $defaults = [
		"notice_banner_type" => "simple",
        "consent_type" => "express",
        "palette" => "light",
        "language" => "cs",
        "page_load_consent_levels" => ["strictly-necessary"],
        "notice_banner_reject_button_hide" => false,
        "preferences_center_close_button_hide" => false,
        "website_name" => "",
        "website_privacy_policy_url" => ""
	];
	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaults);

		$builder->addDefinition($this->prefix('cookieConsentControl'))->setImplement(ICookieConsentControlFactory::class)->setArguments([$config]);

	}


	
}