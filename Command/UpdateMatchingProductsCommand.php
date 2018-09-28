<?php

/*
 * This file is part of the OpenMiamMiam project.
 *
 * (c) Isics <contact@isics.fr>
 *
 * This source file is subject to the AGPL v3 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Isics\Bundle\OpenMiamMiamBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateMatchingProductsCommand extends ContainerAwareCommand
{
    /**
     * @see ContainerAwareCommand
     */
    protected function configure()
    {
        $this->setName('openmiammiam:update-matching-products')
             ->setDescription('Fill table with the most sold product in same orders for every given products');
    }

    /**
     * @see ContainerAwareCommand
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Computing matching products...</comment>');
        $output->writeln('');

        $progressBar = new ProgressBar($output);
        $progressBar->setBarCharacter('<fg=green>•</>');
        $progressBar->setEmptyBarCharacter('<fg=red>•</>');
        $progressBar->setProgressCharacter('<fg=green>➤</>');
        $progressBar->setFormat(
            "%memory% %current%/%max% [%bar%] %percent:3s%%\n Elapsed : %elapsed% Remaining : %remaining:-6s%"
        );

        $callback = function($i, $nbProducts) use ($progressBar) {
            if ($i === 1) {
                $progressBar->start($nbProducts);
            }
            $progressBar->setCurrent($i);
        };

        $productMatchingManager = $this->getContainer()->get('open_miam_miam.product_matching_manager');
        $productMatchingManager->updateMatchingProducts($callback);
    }
}

