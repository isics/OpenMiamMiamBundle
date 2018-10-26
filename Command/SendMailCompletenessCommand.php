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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SendMailCompletenessCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('openmiammiam:send-mail-completeness')
            ->setDescription('Send Mail with completeness quality info');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mailer = $this->getContainer()->get('open_miam_miam.mailer');
        $translator = $mailer->getTranslator();
        $translator->setLocale($this->getContainer()->getParameter('locale'));

        $associations = $this->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository('IsicsOpenMiamMiamBundle:Association')
            ->findAll();

        foreach($associations as $association)
        {
            $producers = $association->getProducers();

            foreach ($producers as $producer) {
                /**$message = $mailer->getNewMessage();
                $message
                    ->setFrom(array($association->getEmail() => $association->getName()))
                    ->setTo($this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('IsicsOpenMiamMiamUserBundle:User')->findManager($producer)[0]->getEmail())
                    ->setSubject(
                        $mailer->translate(
                            'mail.completeness'
                        )
                    )
                    ->setBody(
                        $mailer->render($this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('IsicsOpenMiamMiamUserBundle:User')->findManager($producer)
                            'IsicsOpenMiamMiamBundle:Mail:ordersClosed.html.twig',
                            array(
                                'salesOrder' => $salesOrder,
                                'branchOccurrence' => $nextBranchOccurrence
                            )
                        ),
                        'text/html'
                    );

                $mailer->send($message);

                ++$mailNumber;

                $output->writeln(sprintf('<info>- %s</info>', $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('IsicsOpenMiamMiamUserBundle:User')->findManager($producer)->getEmail()));**/
                $output->writeln(var_dump($producer->getName()));
                $managers = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('IsicsOpenMiamMiamUserBundle:User')->findManager($producer);
                $output->writeln(var_dump(count($managers)));
            }
        }
    }

}
