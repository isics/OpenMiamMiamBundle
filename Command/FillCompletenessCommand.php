<?php

namespace Isics\Bundle\OpenMiamMiamBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Isics\Bundle\OpenMiamMiamBundle\Entity\Product;
use Isics\Bundle\OpenMiamMiamBundle\Entity\ProductInsight;

class FillCompletenessCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('openmiammiam:fill-completeness')
            ->setDescription('Fill completeness fields for products in database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $productManager = $this->getContainer()->get('doctrine')->getRepository('IsicsOpenMiamMiamBundle:Product');
        $products = $productManager->findAll();
        $completeness = 7;
        foreach ($products as $db_product)
        {
          $product = $productManager->find($db_product->getId());
          if ($product->getDescription())
          {
              $completeness++;

              if (strlen($product->getDescription()) < 10)
              {
                  $insight = new ProductInsight();
                  $insight->setType("QUALITY");
                  $insight->setCode(4);
                  $insight->setProductId($product->getId());
                  $product->addProductInsight($insight);
              }

              if ($product->getImage())
              {
                  $completeness++;

                  // Image quality detection here

                  if ($product->getPriceInfo())
                  {
                    $completeness++;

                  } else
                  {
                      $output->writeln('No price info provided');
                  }

              } else
              {
                  $insight = new ProductInsight();
                  $insight->setType("COMPLETENESS");
                  $insight->setCode(2);
                  $insight->setProductId($product->getId());
                  $product->addProductInsight($insight);
              }
          } else
          {
             $insight = new ProductInsight();
             $insight->setType("COMPLETENESS");
             $insight->setCode(1);
             $insight->setProductId($product->getId());
             $product->addProductInsight($insight);

             if (!$product->getImage())
             {
                 $insight = new ProductInsight();
                 $insight->setType("COMPLETENESS");
                 $insight->setCode(2);
                 $insight->setProductId($product->getId());
                 $product->addProductInsight($insight);
             }

             if (!$product->getPriceInfo())
             {
                 $insight = new ProductInsight();
                 $insight->setType("COMPLETENESS");
                 $insight->setCode(3);
                 $insight->setProductId($product->getId());
                 $product->addProductInsight($insight);
             }
          }
          $product->setCompleteness($completeness);
          $this->getContainer()->get('doctrine')->getManager()->flush();
          $completeness = 7;
        }

        $output->writeln("<success> - Finished");
    }

}
