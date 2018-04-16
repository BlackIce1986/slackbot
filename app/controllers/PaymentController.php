<?php

class PaymentController extends ControllerBase
{

    public function indexAction()
    {


    }

    public function newAction()
    {

        $responseArray = [];

        if ( $this->request->isPost() === false ) {
            $responseArray = array(
                'text' => 'Only post request is allowed'
            );
            $this->stopAndSend($responseArray);
        }


        $token       = $this->request->getPost('token','string') ?? $this->request->get('token','string');

        if ($token != $this->config->slack->token) {
            $responseArray = array(
                'text' => 'Bad-bad token '.$token.' != '.$this->config->slack->token
            );
           $this->stopAndSend($responseArray);
        }

        $requestText = $this->request->getPost('text') ??  $this->request->get('text');
        $slackUserId = $this->request->getPost('user_id','string') ?? $this->request->get('user_id','string');
        $userName    = $this->request->getPost('user_name','string') ?? $this->request->get('user_name','string');
        $teamId      = $this->request->getPost('team_id','string') ?? $this->request->get('team_id','string');
        $channelId   = $this->request->getPost('channel_id','string') ?? $this->request->get('channel_id','string');
        $channelName = $this->request->getPost('channel_name','string') ?? $this->request->get('channel_name','string');

        $callbackUrl = $this->request->getPost('response_url','string') ?? $this->request->get('response_url','string');
        $triggerId   = $this->request->getPost('trigger_id','string') ?? $this->request->get('trigger_id','string');

        preg_match("!\d+(?:\.\d+)?([a-zA-Z]{3,})!",$requestText, $paymentAmount);

        $invoiceCurrency = $paymentAmount[1] ?? null;
        $invoicePrice    = str_replace($invoiceCurrency,'',$paymentAmount[0]);


        if ( $invoiceCurrency === null ) {
            $responseArray = array(
              'text' => 'Can not find payment amount',
              'attachments' => array(
                  ['text' => 'Please use 123EUR, or 0.124355BTC format']
              )
            );
            $this->stopAndSend($responseArray);
        } else {

            //store invoice and user info
            $userObj = Users::findFirst(array(
                'conditions' => 'user_id=?1 AND team_id=?2',
                'bind' => array(
                    1 => $slackUserId,
                    2 => $teamId
                )
            ));

            $userId = $userObj->id ?? 0;


            $newPayment = new \Library\Invoice\Invoice('blockio');

            $invoiceModel = (new \Library\Invoice\InvoiceModel())
                ->setUserId($userId)
                ->setDescription($requestText)
                ->setCurrency($invoiceCurrency)
                ->setBalance(0)
                ->setPrice($invoicePrice)
                ->setCompleted(0)
                ->setCallbackUrl($callbackUrl)
                ->setTriggerId($triggerId)
                ->setAddressLabel($requestText.' on '.date("d-m-Y_H.i.s"));

            if ( mb_strtoupper($invoiceCurrency) != "BTC" ) {
                list($status, $result) = $newPayment->getRate(mb_strtoupper($invoiceCurrency));

                if ( $status === false ) {

                    $responseArray = array(
                        'text' => $result
                    );

                    $this->stopAndSend($responseArray);

                } else {

                    // TODO: Calculate AVG of exchange rate

                    $currencyRatio   = $invoicePrice/$result->data->prices[0]->price;
                    $currentPrice    = 1 * $currencyRatio;
                    $currentCurrency = "BTC";

                    $invoiceModel
                        ->setOriginalCurrency($invoiceModel->getCurrency())
                        ->setOriginalPrice($invoiceModel->getPrice())
                        ->setPrice($currentPrice)
                        ->setCurrency($currentCurrency);
                }
            }



            try {

                list($status, $response) = $newPayment->create($invoiceModel);

                if ($status) {

                    $invoice = $response;

                    $this->db->begin();


                    if ( $userObj === false ) {
                        $userObj               = new Users();
                        $userObj->user_id      = $slackUserId;
                        $userObj->user_name    = $userName;
                        $userObj->team_id      = $teamId;
                        $userObj->channel_id   = $channelId;
                        $userObj->channel_name = $channelName;


                        if ( $userObj->save() === false ) {
                            $this->db->rollback();

                          throw new Exception("Cant save User");

                        } else {
                            $invoice->setUserId($userObj->readAttribute('id'));
                        }
                    }

                    $invoiceObj = new Invoices();
                    $invoiceObj->modelFromInvoice($invoice);

                    if ( $invoiceObj->save() === false ) {

                        $this->db->rollback();
                        throw new Exception("Cant save Invoice");

                    } else {
                        $invoice->setId($invoiceObj->readAttribute('id'));
                    }


                    $userInvoices = new UserInvoices();
                    $userInvoices->userFromInvoce($invoice);


                    if ( $userInvoices->save() === false ) {
                        $this->db->rollback();

                        throw new Exception("Cant save UserInvoices");

                    }


                    $this->db->commit();

                    $responseArray = $newPayment->formatSuccess($invoice);


                } else {

                    throw new Exception("Cant create Invoice");

                }

            } catch (Exception $e) {
                $responseArray = array(
                    'text'        => 'Could not create invoice',
                    'attachments' => array(
                        ['text' => $e->getMessage()]
                    )
                );
            }


        }

       $this->stopAndSend($responseArray);
    }


    /**
     * Receive notification and update invoice by notification_id
     */
    public function notificationAction()
    {

        $file = BASE_PATH. "/debug/".time().microtime().".txt";

        $debug = file_get_contents($file);

        $data = json_decode(file_get_contents('php://input'), true);

        $notificationId = $data['notification_id'];
        $address        = $data['data']['address'];

        $invoiceObj = Invoices::findFirst(array(
            'conditions' => 'notification_id=?1 AND address=?2',
            'bind' => array(
                1 => $notificationId,
                2 => $address
            )
        ));

        if ($invoiceObj != false) {


            $invoice = $invoiceObj->invoiceFromModel();

            $phql = "SELECT Users.user_id, Users.channel_id FROM UserInvoices LEFT JOIN Users ON UserInvoices.user_id=Users.id WHERE UserInvoices.invoice_id=?1";
            $userObj = $this->modelsManager->executeQuery($phql, [1=>$invoiceObj->id]);


            $paymentGateway = new \Library\Invoice\Invoice('blockio');
            $addressBalance = $paymentGateway->getBalance($invoice);
            $invoice->setBalance($addressBalance);


            if ($invoice->getCompleted() == 1) {
                //Sent slack notification
                list($status, $response) = (new \Library\Slack\Slack($userObj[0]->user_id,$userObj[0]->channel_id))->sendMessage("Your invoice of ".$invoice->getPrice()."BTC is paid in full");

            }

            $invoiceObj = new Invoices();
            $invoiceObj->modelFromInvoice($invoice);
            $invoiceObj->save();


        }



    }


    public function balanceAction()
    {


        $reportingRequest = new \Library\Invoice\Invoice('blockio');
        $balanceList = $reportingRequest->getWalletBalance();
        //print_r($balance);
        reset($balanceList);
        $first = key($balanceList);
        $responseArray = [];
        foreach ($balanceList as $key => $balance) {
            if ($key === $first) {
                $responseArray['text'] = $balance['address'].' - '.$balance['balance'];
            } else {
                $responseArray['attachments'][] = ['text' => $balance['address'].' - '.$balance['balance']];
            }
        }

        $this->stopAndSend($responseArray);

    }

}