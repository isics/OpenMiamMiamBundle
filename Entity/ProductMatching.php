<?php

/*
 * This file is part of the OpenMiamMiam project.
 *
 * (c) Isics <contact@isics.fr>
 *
 * This source file is subject to the AGPL v3 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Isics\Bundle\OpenMiamMiamBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Isics\OpenMiamMiamBundle\Entity\ProductMatching
 *
 * @ORM\Table(
 *     name="product_matching",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(columns={"product_id", "matching_product_id"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="Isics\Bundle\OpenMiamMiamBundle\Entity\Repository\ProductMatchingRepository")
 */
class ProductMatching
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var Product $product
     * 
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false)
     */
    private $product;

    /**
     * @var Product $matchingProduct
     * 
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="matching_product_id", referencedColumnName="id", nullable=false)
     */
    private $matchingProduct;

    /**
     * @var integer $nbCommonOrders
     * 
     * @ORM\Column(name="nb_common_orders", type="integer", nullable=false)
     */
    private $nbCommonOrders;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return ProductMatching
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set matching product
     *
     * @param Product $matchingProduct
     *
     * @return ProductMatching
     */
    public function setMatchingProduct($matchingProduct)
    {
        $this->matchingProduct = $matchingProduct;

        return $this;
    }

    /**
     * Get matching product
     *
     * @return Product
     */
    public function getMatchingProduct()
    {
        return $this->matchingProduct;
    }

    /**
     * Set number of common orders
     *
     * @param integer $nbCommonOrders
     *
     * @return integer
     */
    public function setNbCommonOrders($nbCommonOrders)
    {
        $this->nbCommonOrders = $nbCommonOrders;

        return $this;
    }

    /**
     * Get number of common orders
     *
     * @return integer
     */
    public function getNbCommonOrders()
    {
        return $this->nbCommonOrders;
    }
}
