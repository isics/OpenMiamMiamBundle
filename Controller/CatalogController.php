<?php

/*
 * This file is part of the OpenMiamMiam project.
 *
 * (c) Isics <contact@isics.fr>
 *
 * This source file is subject to the AGPL v3 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Isics\Bundle\OpenMiamMiamBundle\Controller;

use Isics\Bundle\OpenMiamMiamBundle\Entity\Branch;
use Isics\Bundle\OpenMiamMiamBundle\Entity\Category;
use Isics\Bundle\OpenMiamMiamBundle\Entity\Product;
use Isics\Bundle\OpenMiamMiamBundle\Entity\ProductMatching;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CatalogController extends Controller
{
    /**
     * Shows categories
     *
     * @param Branch $branch
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCategoriesAction(Branch $branch)
    {
        $categories = $this->getDoctrine()->getRepository('IsicsOpenMiamMiamBundle:Category')
            ->findLevel1WithProductsInBranch($branch);

        return $this->render('IsicsOpenMiamMiamBundle:Catalog:showCategories.html.twig', array(
            'branch'     => $branch,
            'categories' => $categories,
        ));
    }

    /**
     * Shows a category with its products
     *
     * @ParamConverter("branch",   class="IsicsOpenMiamMiamBundle:Branch",   options={"mapping": {"branchSlug":   "slug"}})
     * @ParamConverter("category", class="IsicsOpenMiamMiamBundle:Category", options={"mapping": {"categorySlug": "slug"}})
     *
     * @param Branch   $branch
     * @param Category $category
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCategoryAction(Branch $branch, Category $category)
    {
        if (0 === $category->getLvl()) {
            throw $this->createNotFoundException('Root node is not visible.');
        }

        if (!$this->getDoctrine()->getRepository('IsicsOpenMiamMiamBundle:Category')->hasProductAvailableInBranch($branch, $category)) {
            throw $this->createNotFoundException('No product was found in this category.');
        }

        return $this->render('IsicsOpenMiamMiamBundle:Catalog:showCategory.html.twig', array(
            'branch'   => $branch,
            'category' => $category,
        ));
    }

    /**
     * Shows product details
     *
     * @ParamConverter("branch", class="IsicsOpenMiamMiamBundle:Branch", options={"mapping": {"branchSlug": "slug"}})
     *
     * @param Branch  $branch      Branch
     * @param string  $productSlug Product slug
     * @param integer $productId   Product id
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showProductAction(Branch $branch, $productSlug, $productId)
    {
        $product = $this->getDoctrine()->getRepository('IsicsOpenMiamMiamBundle:Product')
            ->findOneByIdAndVisibleInBranch($productId, $branch);

        if (null === $product) {
            throw new NotFoundHttpException('Product not found');
        }

        if ($product->getSlug() !== $productSlug) {
            return $this->redirect($this->generateUrl(
                'open_miam_miam.catalog.product',
                array(
                    'branchSlug'  => $branch->getSlug(),
                    'productSlug' => $product->getSlug(),
                    'productId'   => $productId,
                )
            ), 301);
        }

        return $this->render('IsicsOpenMiamMiamBundle:Catalog:showProduct.html.twig', array(
            'branch'  => $branch,
            'product' => $product,
        ));
    }

    /**
     * Shows products of the moment
     *
     * @param Branch  $branch
     * @param integer $limit
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showProductsOfTheMomentAction(Branch $branch, $limit = 3)
    {
        $branchOccurrenceManager = $this->container->get('open_miam_miam.branch_occurrence_manager');
        if (!$branchOccurrenceManager->hasNext($branch)) {
            return new Response();
        }

        $products = $this->getDoctrine()->getRepository('IsicsOpenMiamMiamBundle:Product')
            ->findOfTheMomentForBranchOccurrence($branchOccurrenceManager->getNext($branch), $limit);

        $nbProducts = count($products);

        if (0 === $nbProducts) {
            return new Response();
        }

        return $this->render('IsicsOpenMiamMiamBundle:Catalog:showProductsRow.html.twig', array(
            'cssId'      => 'products-of-the-moment',
            'title'      => 'zone.products_of_the_moment.title',
            'branch'     => $branch,
            'products'   => $products,
            'nbProducts' => $nbProducts,
        ));
    }

    /**
     * Shows products matching with one product or all products in cart
     *
     * @param Branch  $branch
     * @param Product $product
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showMatchingProductsAction(Branch $branch, Product $product = null)
    {
        $branchOccurrence = $this->container->get('open_miam_miam.branch_occurrence_manager')->getNext($branch);
        if ($branchOccurrence === null) {
            return new Response();
        }

        $cart = $this->container->get('open_miam_miam.cart_manager')->get($branch);
        $productsInCart = [];
        foreach ($cart->getItems() as $item) {
            $productsInCart[] = $item->getProduct();
        }

        if ($product === null) {
            $products = $productsInCart;
            $excludedProducts = [];
        } else {
            $products = [$product];
            $excludedProducts = $productsInCart;
        }

        $matchingProducts = $this->getDoctrine()->getRepository(ProductMatching::class)->findMatchingProducts(
            $products,
            $branchOccurrence,
            $excludedProducts
        );

        $nbMatchingProducts = count($matchingProducts);

        if (0 === $nbMatchingProducts) {
            return new Response();
        }

        return $this->render('IsicsOpenMiamMiamBundle:Catalog:showProductsRow.html.twig', [
            'cssId'      => 'matching-products',
            'title'      => 'zone.matching_products.title',
            'branch'     => $branch,
            'products'   => $matchingProducts,
            'nbProducts' => $nbMatchingProducts,
        ]);
    }
}
