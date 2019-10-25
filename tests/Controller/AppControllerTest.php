<?php declare(strict_types=1);

namespace App\Tests\Controller;

use App\Tests\Entity\Category;
use App\Tests\Entity\Product;
use App\Tests\Form\Dto\ProductDto;
use App\Tests\Form\Type\ProductType;
use App\Tests\KernelTestCase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AppControllerTest extends KernelTestCase
{
    public function testMergeDoctrineCollectionListener()
    {
        $entity = $this->createEntity($expected = 2);

        $this->assertEquals($expected, \count($entity->getCategories()));
        $this->handleForm($entity);

        $em = self::$container->get(EntityManagerInterface::class);
        $em->refresh($entity);
        $this->assertEquals(0, \count($entity->getCategories()));
    }

    private function createEntity(int $length = 2): Product
    {
        $em = self::$container->get(EntityManagerInterface::class);

        $collection = new ArrayCollection();
        for ($i = 0; $i < $length; $i++) {
            $category = new Category();
            $collection->add($category);
            $em->persist($category);
        }

        $product = new Product();
        $product->setCategories($collection);
        $em->persist($product);
        $em->flush();

        return $product;
    }

    private function handleForm(Product $entity): void
    {
        $dto = new ProductDto($entity);
        $form = self::$container->get('form.factory')->create(ProductType::class, $dto);
        $request = Request::create("/", 'POST', [
            'product' => [
                'categories' => [],
                'param' => $expected = 'test',
            ],
        ]);
        $form->handleRequest($request);
        $entity->setCategories($dto->getCategories());

        $em = self::$container->get(EntityManagerInterface::class);
        $em->persist($entity);
        $em->flush();
    }
}