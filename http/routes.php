<?php

\Route::group(['middleware' => 'auth'], function () {
    \Route::get('incontact/admin/login', 'Frankkessler\Incontact\Controllers\IncontactController@login_form');
    \Route::get('incontact/admin/callback', 'Frankkessler\Incontact\Controllers\IncontactController@process_authorization_callback');
});
