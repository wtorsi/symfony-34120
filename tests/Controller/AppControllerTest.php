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

        $this->assertEquals($expected, \count($entity->getCategories()), 'Check initial categories length.');
        $this->handleForm($entity);

        $em = self::$container->get(EntityManagerInterface::class);
        $em->refresh($entity);
        $this->assertEquals(0, \count($entity->getCategories()), 'Check categories length after submit.');
    }

    public function testWithoutMergeDoctrineCollectionListener()
    {
        $entity = $this->createEntity($expected = 2);

        $categories = $entity->getCategories();
        $category = $categories->first();
        $this->assertEquals($expected, \count($categories), 'Check initial categories length.');

        $this->handleForm($entity, [$category]);

        $em = self::$container->get(EntityManagerInterface::class);
        $em->refresh($entity);
        $this->assertEquals(1, \count($entity->getCategories()), 'Check categories length after submit.');
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

    private function handleForm(Product $entity, array $categories = []): void
    {
        $dto = new ProductDto($entity);
        $form = self::$container->get('form.factory')->create(ProductType::class, $dto);
        $request = Request::create("/", 'POST', [
            'product' => [
                'categories' => \array_map(function (Category $category) {
                    return $category->getId();
                }, $categories),
            ],
        ]);
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $entity->setCategories($dto->getCategories());
            $em = self::$container->get(EntityManagerInterface::class);
            $em->persist($entity);
            $em->flush();

            return;
        }

        $this->markTestIncomplete('Form return errors.');
    }
}