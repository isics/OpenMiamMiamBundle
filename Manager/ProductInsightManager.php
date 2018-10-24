<?php
/*
 * This file is part of the OpenMiamMiam project.
 *
 * (c) Isics <contact@isics.fr>
 *
 * This source file is subject to the AGPL v3 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Isics\Bundle\OpenMiamMiamBundle\Manager;

use Isics\Bundle\OpenMiamMiamBundle\Entity\ProductInsight;
use Doctrine\ORM\EntityManager;

/**
 * Class ProductInsightRepository
 *
 * @package Isics\Bundle\OpenMiamMiamBundle\Manager
 */
class ProductInsightManager
{
    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;

    /**
     * Constructs object
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
      * Update Products Insights
      * @param \Closure $callback
      */
    public function updateProductInsights(\Closure $callback = null)
    {
        $productManager = $this->entityManager->getRepository('IsicsOpenMiamMiamBundle:Product');
        $products = $productManager->findAll();
        $completeness = 7;
        foreach ($products as $db_product) {
            $product = $productManager->find($db_product->getId());
            if ($product->getDescription()) {
                $completeness++;

                if (strlen($product->getDescription()) < 10) {
                    $insight = new ProductInsight();
                    $insight->setType("QUALITY");
                    $insight->setCode(4);
                    $insight->setProductId($product->getId());
                    $product->addProductInsight($insight);
                }

                if ($product->getImage()) {
                    $completeness++;

                    // Image quality detection here

                    if ($product->getPriceInfo()) {
                        $completeness++;
                    }
                } else {
                    $insight = new ProductInsight();
                    $insight->setType("COMPLETENESS");
                    $insight->setCode(2);
                    $insight->setProductId($product->getId());
                    $product->addProductInsight($insight);
                }
            } else {
                $insight = new ProductInsight();
                $insight->setType("COMPLETENESS");
                $insight->setCode(1);
                $insight->setProductId($product->getId());
                $product->addProductInsight($insight);

                if (!$product->getImage()) {
                    $insight = new ProductInsight();
                    $insight->setType("COMPLETENESS");
                    $insight->setCode(2);
                    $insight->setProductId($product->getId());
                    $product->addProductInsight($insight);
                }

                if (!$product->getPriceInfo()) {
                    $insight = new ProductInsight();
                    $insight->setType("COMPLETENESS");
                    $insight->setCode(3);
                    $insight->setProductId($product->getId());
                    $product->addProductInsight($insight);
                }
            }
            $product->setCompleteness($completeness);
            $this->entityManager->flush();
            $completeness = 7;
            $callback();
        }
    }

    /**
     * Return the count of products
     *
     * @return int
     */
    public function count()
    {
        $productManager = $this->entityManager->getRepository('IsicsOpenMiamMiamBundle:Product');
        return $productManager->count();
    }
}
