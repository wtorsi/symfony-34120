<?php declare(strict_types=1);

namespace App\Tests\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_EXPLICIT")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Category
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="string", length=23, nullable=false)
     */
    private $id;

    public function __construct()
    {
        $this->id = \uniqid('', true);
    }
}