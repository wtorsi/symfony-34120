<?php declare(strict_types=1);

namespace App\Tests;

abstract class KernelTestCase extends \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase
{
    protected function setUp()
    {
        self::bootKernel();
    }
}