<?php

namespace Frankkessler\Incontact\Apis;

class RealTimeDataApi extends Base
{
    /**
     * Get current real-time agent states
     *
     * array['query_array']         array Defines query structure for api call
     *          ['updatedSince']    string Date string
     *          ['fields']          string  List of fields to return from api
     *
     * @param array $query_array Structure:(See Above)
     *
     * @return array
     */
    public function agents_states($query_array=[])
    {
        $query_string = http_build_query($query_array);
        return $this->client->get('agents/states?'.$query_string);
    }

    /**
     * Get current real-time contact states
     *
     * array['query_array']         array Defines query structure for api call
     *          ['updatedSince']    string Date string
     *          ['fields']          string  List of fields to return from api
     *          ['mediaTypeId']     integer Filter api return by media type
     *          ['skillId']         integer Filter api return by skill
     *          ['campaignId']      integer Filter api return by campaign
     *          ['agentId']         integer Filter api return by agent
     *          ['teamId']          integer Filter api return by team
     *          ['toAddr']          string Filter api return by to address
     *          ['fromAddr']        string Filter api return by from address
     *
     * @param array $query_array Structure:(See Above)
     *
     * @return array
     */
    public function contacts_states($query_array=[])
    {
        $query_string = http_build_query($query_array);
        return $this->client->get('contacts/states?'.$query_string);
    }

    /**
     * Get current real-time agent skills activity
     *
     * array['query_array']         array Defines query structure for api call
     *          ['updatedSince']    string Date string
     *          ['fields']          string  List of fields to return from api
     *
     * @param array $query_array Structure:(See Above)
     *
     * @return array
     */
    public function skills_activity($query_array=[])
    {
        $query_string = http_build_query($query_array);
        return $this->client->get('skills/activity?'.$query_string);
    }
}