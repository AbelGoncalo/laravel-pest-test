<?php

use function Pest\Laravel\getJson;

it('should return status code 200', function(){

    getJson('/',[
        'content-type' => 'application/json',

    ])->assertOk(200);


});
