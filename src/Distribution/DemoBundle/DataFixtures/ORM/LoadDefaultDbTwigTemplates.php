<?php

namespace Distribution\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

use Difane\Bundle\TwigDatabaseBundle\Entity\Template;

class LoadDefaultDbTwigTemplates implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $t = new Template();
        $t->setName("some db template");
        $t->setContent("
{% for i in range(0, 3) %}
    {{ i }},
{% endfor %}");

    	$manager->persist($t);
        $manager->flush();
    }
}