<?php
declare(strict_types = 1);

namespace Clear01\CookieConsent\DI;

use Clear01\CookieConsent\Control\CokieConsent\ICookieConsentControlFactory;
use Clear01\CookieConsent\Subscriber\CookieConsentSubscriber;
use Kdyby\Doctrine\DI\IEntityProvider;
use Kdyby\Events\DI\EventsExtension;
use Nette\DI\CompilerExtension;
use Zenify\DoctrineMigrations\IMigrationsProvider;

class CookieConsentExtension extends CompilerExtension implements IMigrationsProvider, IEntityProvider
{
	/** @var array|object */
	protected $defaults = [
		"notice_banner_type" => "simple",
        "consent_type" => "express",
        "palette" => "light",
        "reload_on_denied_days" => null,
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
		$builder->addDefinition($this->prefix('cookieConsentSubscriber'))->setFactory(CookieConsentSubscriber::class)->addTag(EventsExtension::TAG_SUBSCRIBER);
	}

	public function getMigrationsDir(): string
	{
		return __DIR__ . '/../../migrations';
	}

	/**
	 * Returns associative array of Namespace => mapping definition
	 *
	 * @return array
	 */
	public function getEntityMappings(): array
	{
		return [
			'Clear01\CookieConsent' => dirname(__DIR__),
		];
	}

	
}