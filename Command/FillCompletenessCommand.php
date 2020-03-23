<?php

namespace Isics\Bundle\OpenMiamMiamBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
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
        $output->writeln('<comment>Computing products insights...</comment>');
        $output->writeln('');

        $productInsightManager = $this->getContainer()->get('open_miam_miam.product_insight_manager');

        $progressBar = new ProgressBar($output, $productInsightManager->count());
        $progressBar->setBarCharacter('<fg=green>•</>');
        $progressBar->setEmptyBarCharacter('<fg=red>•</>');
        $progressBar->setProgressCharacter('<fg=green>➤</>');
        $progressBar->setFormat(
            "%memory% %current%/%max% [%bar%] %percent:3s%%\n Elapsed : %elapsed% Remaining : %remaining:-6s%"
        );

        $callback = function() use ($progressBar) {
            $progressBar->advance(1);
        };

        $productInsightManager->updateProductInsights($callback);
    }

}
