<?php
declare(strict_types = 1);

namespace Clear01\CookieConsent\DI;

use Clear01\CookieConsent\Control\CokieConsent\CookieConsentControl;
use Clear01\CookieConsent\Control\CokieConsent\ICookieConsentControlFactory;
use Clear01\CookieConsent\Subscriber\CookieConsentSubscriber;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;
use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use function dirname;

class CookieConsentExtension extends CompilerExtension
{
	public function getConfigSchema(): \Nette\Schema\Schema
	{
		return Expect::structure([
			"notice_banner_type" => Expect::string("simple"),
			"consent_type" => Expect::string("express"),
			"palette" => Expect::string("light"),
			"reload_on_denied_days" => Expect::int()->nullable(),
			"language" => Expect::string("cs"),
			"page_load_consent_levels" => Expect::arrayOf(Expect::string())->default(["strictly-necessary"]),
			"notice_banner_reject_button_hide" => Expect::bool(false),
			"preferences_center_close_button_hide" => Expect::bool(false),
			"website_name" => Expect::string(""),
			"website_privacy_policy_url" => Expect::string(""),
			"migrations" => Expect::bool(true),
			"orm" => Expect::bool(true),
		]);
	}

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = (array) $this->getConfig();

		$builder->addFactoryDefinition($this->prefix('cookieConsentControl'))
			->setImplement(ICookieConsentControlFactory::class)
			->getResultDefinition()
			->setFactory(CookieConsentControl::class, [$config]);

		$builder->addDefinition($this->prefix('cookieConsentSubscriber'))
			->setFactory(CookieConsentSubscriber::class);
	}

	public function beforeCompile(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();

		if ($config->migrations) {
			$migrationsConfiguration = $builder->getDefinitionByType(Configuration::class);
			$migrationsConfiguration->addSetup('addMigrationsDirectory', ['Clear01\CookieConsent\Migrations', __DIR__ . '/../../migrations']);
		}

		if ($config->orm) {
			$mappingDriverChain = $builder->getDefinitionByType(MappingDriverChain::class);
			$attributeDriver = $builder->addDefinition($this->prefix('orm.attributeDriver'))
				->setFactory(AttributeDriver::class, [[dirname(__DIR__) . '/Entity']])
				->setAutowired(false);

			$mappingDriverChain->addSetup('addDriver', [$attributeDriver, 'Clear01\CookieConsent\Entity']);
		}
	}


	
}