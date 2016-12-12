<?php
namespace App\Controller;

use TC\Controller\Controller;

class DefaultController extends Controller
{

    public function index()
    {
        $this->set('myVar', 'Hello world');
        $this->template='Default/index';
    }

    public function phpinfo()
    {
        phpinfo();
    }
}
