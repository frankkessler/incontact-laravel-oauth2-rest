<?php

namespace Frankkessler\Incontact\Apis;

class RealTimeDataApi extends Base
{
    /*
     * contacts_completed
     *
     * Options:
     * startDate
     * endDate
     * updatedSince
     * fields
     * skip
     * top
     * orderBy
     * mediaTypeId
     * skillId
     * campaignId
     * agentId
     * teamId
     * toAddr
     * fromAddr
     * isLogged
     * isRefused
     * isTakeover
     */
    public function contacts_completed($query_array=[])
    {
        $query_string = http_build_query($query_array);
        return $this->client->get('contacts/completed?'.$query_string);
    }

    /*
     * agents states
     *
     * {
          "agentStates": [
            {
              "agentId": 0,
              "agentStateId": 0,
              "agentStateName": "",
              "businessUnitId": 0,
              "contactId": 0,
              "isACW": false,
              "isOutbound": false,
              "firstName": "",
              "fromAddress": "",
              "lastName": "",
              "lastPollTime": "date",
              "lastUpdateTime": "date",
              "mediaName": "",
              "mediaType": 0,
              "openContacts": 0,
              "outStateDescription": "",
              "outStateId": 0,
              "skillId": 0,
              "skillName": "",
              "startDate": "date",
              "stationId": 0,
              "stationPhoneNumber": "",
              "teamId": 0,
              "teamName": "",
              "toAddress": ""
            }
          ]
        }
     */
    public function agents_states()
    {
        return $this->client->get('agents/states');
    }

    /*
     * contacts states
     *
     * {
          "contactStates": [
            {
              "AgentId": 0,
              "BusinessUnitId": 0,
              "CampaignName": "",
              "CampaignId": 0,
              "ContactId": 0,
              "ContactStateCode": 0,
              "CurrentContactState": "",
              "FirstName": "",
              "FromAddr": "",
              "LastName": "",
              "LastPollTime": "date",
              "LastUpdateTime": "date",
              "MasterContactId": 0,
              "MediaName": "",
              "MediaType": 0,
              "SkillName": "",
              "SkillId": 0,
              "StartDate": "date",
              "TeamName": "",
              "TeamId": 0,
              "Toaddr": ""
            }
          ]
        }
     */
    public function contacts_states()
    {
        return $this->client->get('contacts/states');
    }

    /*
     * skills activity
     *
     * {
          "resultSet": {
            "lastPollTime": "date-time",
            "skillActivity": [
              {
                "serverTime": "date",
                "businessUnitId": 0,
                "agentsACW": 0,
                "agentsAvailable": 0,
                "agentsIdle": 0,
                "agentsLoggedIn": 0,
                "agentsUnavailable": 0,
                "agentsWorking": 0,
                "campaignId": 0,
                "campaignName": "",
                "contactsActive": 0,
                "earliestQueueTime": "",
                "isActive": false,
                "inSLA": 0,
                "isOutbound": false,
                "mediaTypeId": 0,
                "mediaTypeName": "",
                "outSLA": 0,
                "queueCount": 0,
                "serviceLevel": 0,
                "serviceLevelGoal": 0,
                "serviceLevelThreshold": 0,
                "skillName": "",
                "skillId": 0
              }
            ]
          }
        }
     */
    public function skills_activity()
    {
        return $this->client->get('skills/activity');
    }
}