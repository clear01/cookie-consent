<?php
declare(strict_types = 1);

namespace Clear01\CookieConsent\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Entity]
class CookieConsent implements TimestampableInterface
{
	use TimestampableTrait;

	#[ORM\Id]
	#[ORM\Column(type: 'string', unique: true)]
	private string $id;

	#[ORM\Column(type: 'string')]
	protected string $ip;

	#[ORM\Column(type: 'array')]
	protected array $consents;


	public function __construct(string $id, string $ip, array $consents)
	{
		$this->id = $id;
		$this->ip = $ip;
		$this->consents = $consents;
	}


}