<?php

namespace Frankkessler\Incontact\Apis;

class ReportingApi extends Base
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
    public function contacts_completed($query_array = [])
    {
        //TODO: Handle 204 http status code for no contacts, yet still good response
        $query_string = http_build_query($query_array);

        return $this->client->get('contacts/completed?'.$query_string);
    }

    /*
     * contact
     */
    public function contact($contact_id)
    {
        return $this->client->get('contacts/'.$contact_id);
    }
}
