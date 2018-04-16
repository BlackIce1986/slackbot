<?php

class ErrorController extends ControllerBase
{

    public function indexAction()
    {

        $this->dispatcher->forward(array('controller'=>'error','action'=>'pageNotFound'));
        return;
    }

    public function pageNotFoundAction()
    {

        $exction = $this->dispatcher->getParam('exception');

        $this->view->setVar('errorMessageForAdmin',$exction);

    }

    public function pageIsNotRespondingAction()
    {


        $exction = $this->dispatcher->getParam('exception');
        $errorMessage = "
        Exception: ".$exction." <BR>
        Controller: ". $this->dispatcher->getParam('controllerName')."<BR>
        Action: ".$this->dispatcher->getParam('actionName')."<BR>
        file: ".$exction->getFile()."<BR>
        line: ".$exction->getLine()."<BR>
        trace: ".$exction->getTraceAsString()."<BR>
        ";
        $this->view->setVar('errorMessageForAdmin',$errorMessage);

        if ( $this->request->isAjax() ) {

            print json_encode(array('status' => 'fail', 'messages' => array($this->translate->query("Error occurred. Please tr again later")), 'debug'=>$errorMessage));
            $this->view->disable();
            $this->response->setContentType('application/json', 'UTF-8');
        }

    }


}

