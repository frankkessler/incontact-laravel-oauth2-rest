<?php

namespace Frankkessler\Incontact\Controllers;

use Illuminate\Http\Request;

use App;

use App\Http\Requests;
use Frankkessler\Incontact\Controllers\BaseController;
use Illuminate\Support\Facades\View;
use Frankkessler\Incontact\Authentication;

class IncontactController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function login_form()
    {
        return Authentication::returnAuthorizationLink();
    }

    public function process_authorization_callback(Request $request){
        if (!$request->has('code')){
            die;
        }
        return Authentication::processAuthenicationCode($request->input('code'));
    }
}
