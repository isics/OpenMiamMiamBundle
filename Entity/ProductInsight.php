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
use Isics\Bundle\OpenMiamMiamBundle\Entity\Product;

/**
 * ProductInsight
 *
 * @ORM\Table(name="product_insight")
 * @ORM\Entity(repositoryClass="Isics\Bundle\OpenMiamMiamBundle\Repository\ProductInsightRepository")
 */
class ProductInsight
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="insights")
     *
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="integer")
     */
    private $code;

    public function __construct($type, $code, Product $product)
    {
        $this->product = $product;
        $this->code = $code;
        $this->type = $type;
        $product->addProductInsight($this);
    }

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
     * @param integer $product
     * @return ProductInsight
     */
    public function setProduct($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get product
     *
     * @return integer
     */
    public function getProduct()
    {
        return $this->productId;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return ProductInsight
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set code
     *
     * @param integer $code
     * @return ProductInsight
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }
}
