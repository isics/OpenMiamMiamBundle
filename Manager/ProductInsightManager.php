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
        $ids = $productManager->findAllId();
        $completeness = 8;
        foreach ($ids as $id) {
            $product = $productManager->findOneBy(["id" => $id[0]['id']]);
            if ($product->getDescription()) {
                $completeness++;

                if (strlen($product->getDescription()) < 10) {
                    $insight = new ProductInsight("QUALITY", 4, $product);
                }

                if ($product->getImage()) {
                    $completeness++;

                    // Image quality detection here
                } else {
                    $insight = new ProductInsight("COMPLETENESS", 2, $product);
                }
            } else {
                $insight = new ProductInsight("COMPLETENESS", 1, $product);

                if (!$product->getImage()) {
                    $insight = new ProductInsight("COMPLETENESS", 2, $product);
                }
            }
            $product->setCompleteness($completeness);
            $this->entityManager->persist($product);
            $this->entityManager->flush();
            $completeness = 8;
            $callback();
        }
    }

    /**
     * Create the product insights
     * @param \Isics\Bundle\OpenMiamMiamBundle\Entity\Product $product
     */
    public function createProductInsight(\Isics\Bundle\OpenMiamMiamBundle\Entity\Product $product)
    {
        $completeness = 8;
        $insights = $product->getProductInsights();

        foreach ($insights as $insight) {
            $this->entityManager->remove($insight);
        }

        if (!$product->getDescription()) {
            $insight = new ProductInsight("COMPLETENESS", 1, $product);
        } else {
            $completeness ++;

            if(strlen($product->getDescription()) < 10) {
                $insight = new ProductInsight("QUALITY", 4, $product);
            }
        }

        if(!$product->getImage()) {
            $insight = new ProductInsight("COMPLETENESS", 2, $product);
        } else {
            $completeness++;
        }

        $product->setCompleteness($completeness);
        $completeness = 8;
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
