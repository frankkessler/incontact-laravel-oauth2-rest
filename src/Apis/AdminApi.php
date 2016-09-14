<?php

namespace Frankkessler\Incontact\Apis;

class AdminApi extends Base
{
    /*
     * agents
     */
    public function agents(){
        return $this->client->get('agents');
    }

    /*
     * points_of_contact
     */
    public function points_of_contact(){
        return $this->client->get('points-of-contact');
    }

    /*
     * skills
     */
    public function skills(){
        return $this->client->get('skills');
    }

    /*
     * dispositions by skill
     */
    public function dispositions_by_skill($skill_id){
        return $this->client->get('skills/'.$skill_id.'/dispositions');
    }
}