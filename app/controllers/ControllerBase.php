<?php

use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{

    function stopAndSend($array = [])
    {
        print json_encode($array);
        $this->view->disable();
        $this->response->setContentType('application/json', 'UTF-8');
        return;
    }
}
