<?php

namespace Distribution\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

//use Sonata\MediaBundle\Entity\MediaManager;

use Application\Sonata\MediaBundle\Entity\Media;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadDefaultMedia implements FixtureInterface, ContainerAwareInterface
{
	private $container;

	public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
    	$mediaManager = $this->container->get("sonata.media.manager.media");

        $media1 = new Media();
        $media1->setBinaryContent("Jx6hB-GTLPU");
        $media1->setEnabled(true);
        $media1->setContext("default");
        $mediaManager->save($media1, null, "sonata.media.provider.youtube");

        $media2 = new Media();
        $media2->setBinaryContent("jcgM5Oyyt9w");
        $media2->setContext("default");
        $mediaManager->save($media2, null, "sonata.media.provider.youtube");

        $media3 = new Media();
        $media3->setBinaryContent("-MS05zvFHm4");
        $media3->setContext("default");
        $mediaManager->save($media3, null, "sonata.media.provider.youtube");
    }
}