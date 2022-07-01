<?php
declare(strict_types = 1);

namespace Clear01\CookieConsent\Subscriber;

use Clear01\CookieConsent\Entity\CookieConsent;
use DateTime;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Subscriber;
use Nette\Application\Application;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\Utils\Json;
use Throwable;
use Tracy\Debugger;

class CookieConsentSubscriber implements Subscriber
{

	/** @var \Kdyby\Doctrine\EntityManager */
	private $em;

	/** @var IResponse */
	private $response;

	/** @var IRequest */
	private $request;

	public function __construct(EntityManager $em, IRequest $request, IResponse $response)
	{
		$this->em = $em;
		$this->response = $response;
		$this->request = $request;
	}



	public function onRequest()
	{
		try {
			$token = $this->request->getCookie('cookie_consent_user_consent_token');
			if (!$this->request->getCookie('cookie_consent_saved') && $this->request->getCookie('cookie_consent_user_accepted') && $token) {
				$cokieConsent = $this->em->find(CookieConsent::class, $token);
				if ($cokieConsent === null) {
					$consents = Json::decode($this->request->getCookie('cookie_consent_level'), Json::FORCE_ARRAY);
					$cokieConsent = new CookieConsent($token, $this->request->getRemoteAddress(), $consents);
					$this->em->persist($cokieConsent);
					$this->em->flush();
				}
				$this->response->setCookie('cookie_consent_saved', true, new DateTime('+ 1 year'));
			}
		} catch (Throwable $e) {
			Debugger::log($e);
		}
	}

	public function getSubscribedEvents(): array
	{
		return [Application::class . "::onRequest"];
	}
}
