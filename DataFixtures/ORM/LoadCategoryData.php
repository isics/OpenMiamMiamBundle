<?php

/*
 * This file is part of the OpenMiamMiam project.
 *
 * (c) Isics <contact@isics.fr>
 *
 * This source file is subject to the AGPL v3 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Isics\Bundle\OpenMiamMiamBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Isics\Bundle\OpenMiamMiamBundle\Entity\Category;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->translator = $this->container->get('translator');
        $this->translator->setLocale($this->container->getParameter('locale'));
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $root = new Category();
        $root->setName($this->translator->trans('category.root', array(), 'fixtures'));

        $fruitsAndVegetables = new Category();
        $fruitsAndVegetables->setName($this->translator->trans('category.fruits_and_vegetables', array(), 'fixtures'));
        $fruitsAndVegetables->setParent($root);
        $fruitsAndVegetables->setCategoryType($this->getReference('category_type.food'));
        $this->addReference('category.fruits_and_vegetables', $fruitsAndVegetables);

        $dairyProduce = new Category();
        $dairyProduce->setName($this->translator->trans('category.dairy_produce', array(), 'fixtures'));
        $dairyProduce->setParent($root);
        $dairyProduce->setCategoryType($this->getReference('category_type.food'));
        $this->addReference('category.dairy_produce', $dairyProduce);

        $meat = new Category();
        $meat->setName($this->translator->trans('category.meat', array(), 'fixtures'));
        $meat->setParent($root);
        $meat->setCategoryType($this->getReference('category_type.food'));
        $this->addReference('category.meat', $meat);

        $beef = new Category();
        $beef->setName($this->translator->trans('category.beef', array(), 'fixtures'));
        $beef->setParent($meat);
        $beef->setCategoryType($this->getReference('category_type.food'));
        $this->addReference('category.beef', $beef);

        $lamb = new Category();
        $lamb->setName($this->translator->trans('category.lamb', array(), 'fixtures'));
        $lamb->setParent($meat);
        $lamb->setCategoryType($this->getReference('category_type.food'));
        $this->addReference('category.lamb', $lamb);

        $pork = new Category();
        $pork->setName($this->translator->trans('category.pork', array(), 'fixtures'));
        $pork->setParent($meat);
        $pork->setCategoryType($this->getReference('category_type.food'));
        $this->addReference('category.pork', $pork);

        $household = new Category();
        $household->setName($this->translator->trans('category.household', array(), 'fixtures'));
        $household->setParent($root);
        $household->setCategoryType($this->getReference('category_type.household'));
        $this->addReference('category.household', $household);

        $gardening = new Category();
        $gardening->setName($this->translator->trans('category.gardening', array(), 'fixtures'));
        $gardening->setParent($root);
        $gardening->setCategoryType($this->getReference('category_type.gardening'));
        $this->addReference('category.gardening', $gardening);

        $manager->persist($root);
        $manager->persist($fruitsAndVegetables);
        $manager->persist($dairyProduce);
        $manager->persist($meat);
        $manager->persist($beef);
        $manager->persist($lamb);
        $manager->persist($pork);
        $manager->persist($household);
        $manager->persist($gardening);

        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
