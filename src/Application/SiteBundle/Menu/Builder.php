<?php
namespace Application\SiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $security = $this->container->get('security.context');

        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');
        $menu->setCurrentUri($this->container->get('request')->getRequestUri());

        $menu->addChild('Home', array('route' => 'application_site_site_index'));
        $menu->addChild('About', array('route' => 'application_site_site_about'));

        if($security->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            $menu->addChild('Planning Poker', array('route' => 'poker_session'));
        }

        return $menu;
    }
}