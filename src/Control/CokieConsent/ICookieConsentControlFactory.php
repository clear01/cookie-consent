<?php
declare(strict_types = 1);

namespace Clear01\CookieConsent\Control\CokieConsent;

interface ICookieConsentControlFactory
{

	public function create(): CookieConsentControl;

}
