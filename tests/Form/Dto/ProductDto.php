<?php declare(strict_types=1);

namespace App\Tests\Form\Dto;

use App\Tests\Entity\Product;
use Doctrine\Common\Collections\Collection;

class ProductDto
{
    /**
     * @var Collection
     */
    private $categories;
    /**
     * @var string
     */
    private $param;

    public function __construct(Product $entity)
    {
        $this->categories = $entity->getCategories();
    }

    /**
     * @return Collection
     */
    public function getCategories(): ?Collection
    {
        return $this->categories;
    }

    /**
     * @param Collection $categories
     * @return ProductDto
     */
    public function setCategories(?Collection $categories): ProductDto
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * @return string
     */
    public function getParam(): ?string
    {
        return $this->param;
    }

    /**
     * @param string $param
     * @return ProductDto
     */
    public function setParam(?string $param): ProductDto
    {
        $this->param = $param;

        return $this;
    }
}