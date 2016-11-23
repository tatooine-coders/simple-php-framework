<?php

class defaultController extends Controller
{

    public function index()
    {
        die('Index');
    }
    public function test(){
        phpinfo();
    }
}
