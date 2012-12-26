<?php

namespace Application\PlanningPokerBundle\Service;

use Guzzle\Http\Client;

class Jira
{
    public function getTasksByJQL($login, $password, $jql)
    {
        $client = new Client('https://adc.luxoft.com/');
        $client->setSslVerification(false, false, 0);

        $request = $client->get('/jira/rest/api/latest/search?jql='.$jql.'&fields=summary')->setAuth($login, $password);
        $response = $request->send();

        $json = $response->json();
        return $json['issues'];
    }
}
