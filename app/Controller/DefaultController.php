<?php

namespace App\Controller;
use TC\Controller\Controller;

class DefaultController extends Controller
{

    public function index()
    {
        die('Index');
    }
    public function test(){
        phpinfo();
    }
}
