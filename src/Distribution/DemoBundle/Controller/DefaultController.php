<?php

namespace Distribution\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/demo/media")
     * @Template()
     */
    public function mediaAction()
    {
    	$mediaManager = $this->container->get("sonata.media.manager.media");
    	$media = $mediaManager->findOneBy(array('enabled' => true));

        return array('mymedia' => $media);
    }


    /**
     * @Route("/demo/dbtwigtemplates")
     * @Template()
     */
    public function dbTwigTemplatesAction()
    {
    	return array();
    }

    /**
     * @Route("/demo/formatter")
     * @Template()
     */
    public function formatterAction()
    {
    	$staticFormatterData = <<<EOD

*   Candy.
*   Gum.
*   Booze.

I get 10 times more traffic from [Google][1] than from
[Yahoo][2] or [MSN][3].

[1]: http://google.com/        "Google"
[2]: http://search.yahoo.com/  "Yahoo Search"
[3]: http://search.msn.com/    "MSN Search"
EOD;

	$dynamic = $this->getDoctrine()
        ->getRepository('DistributionDemoBundle:Demo')
        ->findOneByName("dynamicContent");

    	return array('static' => $staticFormatterData, 'dynamic' => $dynamic);
    }

    /**
     * @Route("/demo/contentpart")
     * @Template()
     */
    public function contentPartAction()
    {
        $formatterPool = $this->container->get('sonata.formatter.pool');
        $contentPart = $this->getDoctrine()
            ->getRepository('DifaneContentPartBundle:contentPart')
            ->getContentPart('test'); // To get multiple content parts use getContentParts(array('test', 'def'))

        if (!$contentPart) {
            throw $this->createNotFoundException('No contentPart found with name "test"');
        }

        return array('contentPart' => $contentPart);
    }
}
