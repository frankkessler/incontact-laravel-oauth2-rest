<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ReportingApiTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    public function testContact()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], $this->contactSuccess()),
        ]);

        $handler = HandlerStack::create($mock);

        $incontact = new \Frankkessler\Incontact\Incontact([
            'handler'                       => $handler,
            'incontact.oauth.access_token'  => 'TEST',
            'incontact.oauth.refresh_token' => 'TEST',
            'incontact.oauth.expires'       => 9999999999,
            'incontact.base_uri'            => 'api.incontact.com',
        ]);

        $result = $incontact->ReportingApi()->contact('2789879875');

        foreach ($result as $record) {
            $this->assertEquals('2789879875', $record['contactId']);
        }
    }

    public function testCompletedContacts()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], $this->contactCompletedSuccess()),
        ]);

        $handler = HandlerStack::create($mock);

        $incontact = new \Frankkessler\Incontact\Incontact([
            'handler'                       => $handler,
            'incontact.oauth.access_token'  => 'TEST',
            'incontact.oauth.refresh_token' => 'TEST',
            'incontact.oauth.expires'       => 9999999999,
            'incontact.base_uri'            => 'api.incontact.com',
        ]);

        $startDate = date_create_from_format('Y-m-d H:i:s', '2016-09-13 16:29:31', new \DateTimeZone('UTC'));
        $endDate = date_create_from_format('Y-m-d H:i:s', '2016-09-13 16:31:31', new \DateTimeZone('UTC'));

        $options = [
            'startDate' => $startDate->format('c'),
            'endDate'   => $endDate->format('c'),
        ];

        $result = $incontact->ReportingApi()->contacts_completed($options);

        $this->assertTrue(is_array($result['resultSet']['completedContacts']));

        $i = 1;
        foreach ($result['resultSet']['completedContacts'] as $record) {
            if ($i == 1) {
                $this->assertEquals('2789879875', $record['contactId']);
            } else {
                $this->assertEquals('2789879876', $record['contactId']);
            }
            $i++;
        }
    }

    public function contactSuccess()
    {
        return
            '{
                "resultSet":
                {
                    "abandoned":"False",
                    "abandonSeconds":"0",
                    "ACWSeconds":"187",
                    "agentId":"999999",
                    "agentSeconds":"277",
                    "businessUnitId":"9999999",
                    "callbackTime":"0",
                    "campaignId":"999999",
                    "campaignName":"Main Campaign",
                    "confSeconds":"0",
                    "contactId":"2789879875",
                    "contactStart":"2016-09-13T16:29:31.443Z",
                    "dateACWWarehoused":"2016-09-13T16:40:13.430Z",
                    "dateContactWarehoused":"2016-09-13T16:35:13.497Z",
                    "dispositionNotes":"Call went well.  Will followup in 7 days.",
                    "firstName":"Test",
                    "fromAddr":"5555558888",
                    "holdCount":"0",
                    "holdSeconds":"0",
                    "inQueueSeconds":"13",
                    "isActive":"False",
                    "isLogged":"True",
                    "isOutbound":"False",
                    "isRefused":"False",
                    "isShortAbandon":"False",
                    "isTakeover":"False",
                    "isWarehoused":"True",
                    "lastName":"Agent",
                    "lastUpdateTime":"2016-09-13T16:34:25.990Z",
                    "masterContactId":"2789879875",
                    "mediaType":"4",
                    "mediaTypeName":"Call",
                    "pointOfContactId":"3189288",
                    "pointOfContactName":"888-888-9999 Customer Line",
                    "postQueueSeconds":"0",
                    "preQueueSeconds":"5",
                    "primaryDispositionId":"93",
                    "refuseReason":"",
                    "refuseTime":"",
                    "releaseSeconds":"0",
                    "routingTime":"12706",
                    "secondaryDispositionId":"0",
                    "serviceLevelFlag":"0",
                    "skillId":"99999",
                    "skillName":"Main",
                    "state":"EndContact",
                    "stateId":"18",
                    "teamId":"99999",
                    "teamName":"Call Center",
                    "toAddr":"8888889999",
                    "totalDurationSeconds":"295",
                    "transferIndicatorId":"0",
                    "transferIndicatorName":"None"
                }
            }';
    }

    public function contactCompletedSuccess()
    {
        return
            '{
                "resultSet":
                {
                    "completedContacts":
                    [
                        {
                            "abandoned":"False",
                            "abandonSeconds":"0",
                            "ACWSeconds":"187",
                            "agentId":"999999",
                            "agentSeconds":"277",
                            "businessUnitId":"9999999",
                            "callbackTime":"0",
                            "campaignId":"999999",
                            "campaignName":"Main Campaign",
                            "confSeconds":"0",
                            "contactId":"2789879875",
                            "contactStart":"2016-09-13T16:29:31.443Z",
                            "dateACWWarehoused":"2016-09-13T16:40:13.430Z",
                            "dateContactWarehoused":"2016-09-13T16:35:13.497Z",
                            "dispositionNotes":"Call went well.  Will followup in 7 days.",
                            "firstName":"Test",
                            "fromAddr":"5555558888",
                            "holdCount":"0",
                            "holdSeconds":"0",
                            "inQueueSeconds":"13",
                            "isActive":"False",
                            "isLogged":"True",
                            "isOutbound":"False",
                            "isRefused":"False",
                            "isShortAbandon":"False",
                            "isTakeover":"False",
                            "isWarehoused":"True",
                            "lastName":"Agent",
                            "lastUpdateTime":"2016-09-13T16:34:25.990Z",
                            "masterContactId":"2789879875",
                            "mediaType":"4",
                            "mediaTypeName":"Call",
                            "pointOfContactId":"3189288",
                            "pointOfContactName":"888-888-9999 Customer Line",
                            "postQueueSeconds":"0",
                            "preQueueSeconds":"5",
                            "primaryDispositionId":"93",
                            "refuseReason":"",
                            "refuseTime":"",
                            "releaseSeconds":"0",
                            "routingTime":"12706",
                            "secondaryDispositionId":"0",
                            "serviceLevelFlag":"0",
                            "skillId":"99999",
                            "skillName":"Main",
                            "state":"EndContact",
                            "stateId":"18",
                            "teamId":"99999",
                            "teamName":"Call Center",
                            "toAddr":"8888889999",
                            "totalDurationSeconds":"295",
                            "transferIndicatorId":"0",
                            "transferIndicatorName":"None"
                        },
                        {
                            "abandoned":"False",
                            "abandonSeconds":"0",
                            "ACWSeconds":"187",
                            "agentId":"999999",
                            "agentSeconds":"277",
                            "businessUnitId":"9999999",
                            "callbackTime":"0",
                            "campaignId":"999999",
                            "campaignName":"Main Campaign",
                            "confSeconds":"0",
                            "contactId":"2789879876",
                            "contactStart":"2016-09-13T16:30:31.443Z",
                            "dateACWWarehoused":"2016-09-13T16:41:13.430Z",
                            "dateContactWarehoused":"2016-09-13T16:36:13.497Z",
                            "dispositionNotes":"Call went well.  Will followup in 7 days.",
                            "firstName":"Test",
                            "fromAddr":"5555558888",
                            "holdCount":"0",
                            "holdSeconds":"0",
                            "inQueueSeconds":"13",
                            "isActive":"False",
                            "isLogged":"True",
                            "isOutbound":"False",
                            "isRefused":"False",
                            "isShortAbandon":"False",
                            "isTakeover":"False",
                            "isWarehoused":"True",
                            "lastName":"Agent",
                            "lastUpdateTime":"2016-09-13T16:35:25.990Z",
                            "masterContactId":"2789879876",
                            "mediaType":"4",
                            "mediaTypeName":"Call",
                            "pointOfContactId":"3189288",
                            "pointOfContactName":"888-888-9999 Customer Line",
                            "postQueueSeconds":"0",
                            "preQueueSeconds":"5",
                            "primaryDispositionId":"93",
                            "refuseReason":"",
                            "refuseTime":"",
                            "releaseSeconds":"0",
                            "routingTime":"12706",
                            "secondaryDispositionId":"0",
                            "serviceLevelFlag":"0",
                            "skillId":"99999",
                            "skillName":"Main",
                            "state":"EndContact",
                            "stateId":"18",
                            "teamId":"99999",
                            "teamName":"Call Center",
                            "toAddr":"8888889999",
                            "totalDurationSeconds":"295",
                            "transferIndicatorId":"0",
                            "transferIndicatorName":"None"
                        }
                    ]
                }
            }';
    }
}
