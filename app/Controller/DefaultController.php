<?php

namespace App\Controller;
use TC\Controller\Controller;

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
