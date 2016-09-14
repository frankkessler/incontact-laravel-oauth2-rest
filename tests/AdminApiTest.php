<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class AdminApiTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    public function testAgents()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], $this->agentsSuccess()),
        ]);

        $handler = HandlerStack::create($mock);

        $incontact = new \Frankkessler\Incontact\Incontact([
            'handler'                       => $handler,
            'incontact.oauth.access_token'  => 'TEST',
            'incontact.oauth.refresh_token' => 'TEST',
            'incontact.oauth.expires'       => 9999999999,
            'incontact.base_uri'            => 'api.incontact.com',
        ]);

        $result = $incontact->AdminApi()->agents();

        $this->assertTrue(is_array($result['agents']));

        $i = 1;
        foreach ($result['agents'] as $record) {
            if ($i == 1) {
                $this->assertEquals('999999', $record['AgentId']);
            } else {
                $this->assertEquals('999998', $record['AgentId']);
            }
            $i++;
        }
    }

    public function testPointsOfContact()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], $this->pointsOfContactSuccess()),
        ]);

        $handler = HandlerStack::create($mock);

        $incontact = new \Frankkessler\Incontact\Incontact([
            'handler'                       => $handler,
            'incontact.oauth.access_token'  => 'TEST',
            'incontact.oauth.refresh_token' => 'TEST',
            'incontact.oauth.expires'       => 9999999999,
            'incontact.base_uri'            => 'api.incontact.com',
        ]);

        $result = $incontact->AdminApi()->points_of_contact();

        $this->assertTrue(is_array($result['pointsOfContact']));

        $i = 1;
        foreach ($result['pointsOfContact'] as $record) {
            if ($i == 1) {
                $this->assertEquals('9999999', $record['ContactCode']);
            } else {
                $this->assertEquals('9999998', $record['ContactCode']);
            }
            $i++;
        }
    }

    public function testSkills()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], $this->skillsSuccess()),
        ]);

        $handler = HandlerStack::create($mock);

        $incontact = new \Frankkessler\Incontact\Incontact([
            'handler'                       => $handler,
            'incontact.oauth.access_token'  => 'TEST',
            'incontact.oauth.refresh_token' => 'TEST',
            'incontact.oauth.expires'       => 9999999999,
            'incontact.base_uri'            => 'api.incontact.com',
        ]);

        $result = $incontact->AdminApi()->skills();

        $this->assertTrue(is_array($result['skills']));

        $i = 1;
        foreach ($result['skills'] as $record) {
            if ($i == 1) {
                $this->assertEquals('999999', $record['SkillId']);
            } else {
                $this->assertEquals('999998', $record['SkillId']);
            }
            $i++;
        }
    }

    public function testSkillDispositions()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response(200, [], $this->skillDispositionsSuccess()),
        ]);

        $handler = HandlerStack::create($mock);

        $incontact = new \Frankkessler\Incontact\Incontact([
            'handler'                       => $handler,
            'incontact.oauth.access_token'  => 'TEST',
            'incontact.oauth.refresh_token' => 'TEST',
            'incontact.oauth.expires'       => 9999999999,
            'incontact.base_uri'            => 'api.incontact.com',
        ]);

        $skillId = '999999';

        $result = $incontact->AdminApi()->dispositions_by_skill($skillId);

        $this->assertTrue(is_array($result['skillDispositions']['dispositions']));

        $this->assertEquals($skillId, $result['skillDispositions']['skillId']);
        $i = 1;
        foreach ($result['skillDispositions']['dispositions'] as $record) {
            if ($i == 1) {
                $this->assertEquals('1', $record['dispositionId']);
            } else {
                $this->assertEquals('2', $record['dispositionId']);
            }
            $i++;
        }
    }

    public function agentsSuccess()
    {
        return
            '{
                "agents":
                [
                    {
                        "BusinessUnitId":9999999,
                        "AgentId":999999,
                        "UserName":"",
                        "FirstName":"Administrator",
                        "MiddleName":"",
                        "LastName":"",
                        "Email":"",
                        "IsActive":true,
                        "TeamId":999999,
                        "TeamName":"Admin",
                        "ReportToId":null,
                        "ReportToFirstName":"",
                        "ReportToMiddleName":"",
                        "ReportToLastName":"",
                        "IsSupervisor":true,
                        "LastLogin":null,
                        "LastModified":"2015-08-18T19:03:46.21Z",
                        "Location":"",
                        "Custom1":"",
                        "Custom2":"",
                        "Custom3":"",
                        "Custom4":"",
                        "Custom5":"",
                        "InternalId":""
                    },
                    {
                        "BusinessUnitId":9999999,
                        "AgentId":999998,
                        "UserName":"",
                        "FirstName":"Administrator2",
                        "MiddleName":"",
                        "LastName":"",
                        "Email":"",
                        "IsActive":true,
                        "TeamId":999999,
                        "TeamName":"Admin",
                        "ReportToId":null,
                        "ReportToFirstName":"",
                        "ReportToMiddleName":"",
                        "ReportToLastName":"",
                        "IsSupervisor":true,
                        "LastLogin":null,
                        "LastModified":"2015-08-18T19:03:46.21Z",
                        "Location":"",
                        "Custom1":"",
                        "Custom2":"",
                        "Custom3":"",
                        "Custom4":"",
                        "Custom5":"",
                        "InternalId":""
                    }
                ]
            }';
    }

    public function pointsOfContactSuccess()
    {
        return
        '{
            "pointsOfContact":
            [
                {
                    "BusinessUnitId":9999999,
                    "ContactAddress":"8885559999",
                    "ContactCode":9999999,
                    "ContactDescription":"TEST",
                    "DefaultSkillId":999999,
                    "IsActive":true,
                    "MediaTypeName":"Phone Call",
                    "MediaTypeId":4,"Notes":"",
                    "OutboundSkill":false,
                    "ScriptName":"Main IVR"
                },
                {
                    "BusinessUnitId":9999999,
                    "ContactAddress":"8885559998",
                    "ContactCode":9999998,
                    "ContactDescription":"TEST",
                    "DefaultSkillId":999999,
                    "IsActive":true,
                    "MediaTypeName":"Phone Call",
                    "MediaTypeId":4,"Notes":"",
                    "OutboundSkill":false,
                    "ScriptName":"Main IVR"
                }
            ]
        }';
    }

    public function skillsSuccess()
    {
        return
        '{
            "skills":
            [
                {
                    "BusinessUnitId":"9999999",
                    "SkillId":"999999",
                    "SkillName":"Test",
                    "MediaTypeId":"4",
                    "MediaTypeName":"Phone Call",
                    "IsActive":"True",
                    "CampaignId":"123456",
                    "CampaignName":"Test Campaign",
                    "IsDialer":"False",
                    "Notes":"",
                    "UseACW":"True",
                    "UseDisposition":"True",
                    "RequireDisposition":"True",
                    "UseSecondaryDispositions":"False",
                    "OutboundStrategy":"",
                    "IsOutbound":"False",
                    "IsNaturalCallingRunning":"False",
                    "ScriptDisposition":"False",
                    "PriorityBlending":"False",
                    "EmailFromAddress":"",
                    "EmailFromEditable":"False",
                    "ScreenPopTriggerEvent":"",
                    "HoursOfOperationProfileId":""
                },
                {
                    "BusinessUnitId":"9999999",
                    "SkillId":"999998",
                    "SkillName":"Test2",
                    "MediaTypeId":"4",
                    "MediaTypeName":"Phone Call",
                    "IsActive":"True",
                    "CampaignId":"123457",
                    "CampaignName":"Test Campaign2",
                    "IsDialer":"False",
                    "Notes":"",
                    "UseACW":"True",
                    "UseDisposition":"True",
                    "RequireDisposition":"True",
                    "UseSecondaryDispositions":"False",
                    "OutboundStrategy":"",
                    "IsOutbound":"False",
                    "IsNaturalCallingRunning":"False",
                    "ScriptDisposition":"False",
                    "PriorityBlending":"False",
                    "EmailFromAddress":"",
                    "EmailFromEditable":"False",
                    "ScreenPopTriggerEvent":"",
                    "HoursOfOperationProfileId":""
                }
            ]
        }';
    }

    public function skillDispositionsSuccess()
    {
        return
        '{
            "skillDispositions":
            {
                "skillId":"999999",
                "skillName":"Test",
                "dispositions":
                [
                    {
                        "dispositionId":"1",
                        "dispositionName":"Sales Inquiry",
                        "displayOrder":"1",
                        "classification":"",
                        "reportingGroup":"",
                        "systemOutcome":"",
                        "requireCommitmentAmount":"False",
                        "requireRescheduleDate":"False",
                        "agentSpecific":"False"
                    },
                    {
                        "dispositionId":"2",
                        "dispositionName":"Support Inquiry",
                        "displayOrder":"1",
                        "classification":"",
                        "reportingGroup":"",
                        "systemOutcome":"",
                        "requireCommitmentAmount":"False",
                        "requireRescheduleDate":"False",
                        "agentSpecific":"False"
                    }
                ]
            }
        }';
    }
}
