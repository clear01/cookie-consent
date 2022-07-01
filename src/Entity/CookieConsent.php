<?php
declare(strict_types = 1);

namespace Clear01\CookieConsent\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity()
 */
class CookieConsent
{
	use Timestampable;

	/**
	 * @ORM\Id
	 * @ORM\Column(type="string", unique=true)
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	protected $ip;

	/**
	 * @var array
	 * @ORM\Column(type="array")
	 */
	protected $consents;


	public function __construct(string $id, string $ip, array $consents)
	{
		$this->id = $id;
		$this->ip = $ip;
		$this->consents = $consents;
	}


}