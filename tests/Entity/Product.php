<?php declare(strict_types=1);

namespace App\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_EXPLICIT")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Product
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="string", length=23, nullable=false)
     */
    private $id;
    /**
     * @var Category[]
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    private $categories;

    public function __construct()
    {
        $this->id = \uniqid('', true);
        $this->categories = new ArrayCollection();
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setCategories(Collection $collection)
    {
        $this->categories = $collection;
    }
}