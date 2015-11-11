{!! \Form::open(array('action' => '\Frankkessler\Incontact\Controllers\IncontactController@login_form_submit')) !!}
    <div class="input">
        {!! Form::label('username', 'Username') !!}
        {!! \Form::text('username') !!}
    </div>
    <div class="input">
        {!! Form::label('password', 'Password') !!}
        {!! \Form::password('password') !!}
    </div>
    {!! \Form::submit('Submit') !!}
{!! \Form::close() !!}
