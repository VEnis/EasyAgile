<?php

namespace Behat\Context;

use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Context\Step,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Mink\Driver\GoutteDriver;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Feature context.
 */
class DistributionContext extends MinkContext //MinkContext if you want to test web
                  implements KernelAwareInterface
{
    private $kernel;
    private $parameters;

    /**
     * Initializes context with parameters from behat.yml.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function canIntercept()
    {
        $driver = $this->getSession()->getDriver();
        if (!$driver instanceof GoutteDriver) {
            throw new UnsupportedDriverActionException(
                'You need to tag the scenario with '.
                    '"@mink:goutte" or "@mink:symfony". '.
                    'Intercepting the redirections is not '.
                    'supported by %s', $driver
            );
        }
    }

    /**
     * @Given /^(.*) without redirection$/
     */
    public function theRedirectionsAreIntercepted($step)
    {
        $this->canIntercept();
        $this->getSession()->getDriver()->getClient()->followRedirects(false);

        return new Step\Given($step);
    }

    /**
     * @When /^I follow the redirection$/
     * @Then /^I should be redirected$/
     */
    public function iFollowTheRedirection()
    {
        $this->canIntercept();
        $client = $this->getSession()->getDriver()->getClient();
        $client->followRedirects(true);
        $client->followRedirect();
    }

    /**
     * @Given /^I am logged in as "([^"]*)" with "([^"]*)" password$/
     */
    public function iAmLoggedInAsWithPassword($username, $password)
    {
        return array(
            new Step\Given("I am on \"/admin/logout\""),
            new Step\When("I go to \"/admin/dashboard\""),
            new Step\When("I fill in \"username\" with \"$username\""),
            new Step\When("I fill in \"password\" with \"$password\""),
            new Step\When("I press \"Login\""),
            new Step\Then("I should be on \"/admin/dashboard\""),
        );
    }

//
// Place your definition and hook methods here:
//
//    /**
//     * @Given /^I have done something with "([^"]*)"$/
//     */
//    public function iHaveDoneSomethingWith($argument)
//    {
//        $container = $this->kernel->getContainer();
//        $container->get('some_service')->doSomethingWith($argument);
//    }
//
}
