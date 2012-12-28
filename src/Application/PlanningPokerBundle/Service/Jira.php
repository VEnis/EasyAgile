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

    public function updateTasksEstimates($login, $password, $stories)
    {
        foreach($stories as $story)
        {
            $fields = $story->getCustomFields();
            $this->updateTaskEstimate($login, $password, $fields["jira_key"], $story->getEstimate());
        }
    }

    protected function updateTaskEstimate($login, $password, $id, $estimate)
    {
        $client = new Client('https://adc.luxoft.com/');
        $client->setSslVerification(false, false, 0);

        $request = $client->put('/jira/rest/api/latest/issue/'.$id, array('Content-Type'=>"application/json"), json_encode(array(
            "fields" => array("timetracking" => array("originalEstimate" => $estimate."h"))
        )))->setAuth($login, $password);
        $response = $request->send();
    }
}
