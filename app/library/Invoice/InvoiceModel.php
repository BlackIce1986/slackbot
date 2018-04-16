<?php
/**
 * Created by PhpStorm.
 * User: vladislavdavarasvili
 * Date: 13/04/2018
 * Time: 17:55
 */

namespace Library\Invoice;


class InvoiceModel
{

    private $id = null;
    private $address;
    private $addressLabel;
    private $description;
    private $price;
    private $balance;
    private $isCompleted = 0;
    private $currency;
    private $user_id;

    /* Optional info */
    private $original_price = null;
    private $original_currency = null;

    private $notification_key = null;

    /* Slack info */
    private $callback_url;
    private $trigger_id;




    public function __construct()
    {
        return $this;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function setAddressLabel($label)
    {

        $label = preg_replace( "/[^a-z0-9]/i", "", $label );
        $label = preg_replace( '/  +/', ' ', $label );
        $label = str_replace( " ", "-", $label );
        $this->addressLabel = $label;
        return $this;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function setBalance($balance){
        $this->balance = $balance;
        if ($this->balance >= $this->price) {
            $this->setCompleted(1);
        }
        return $this;
    }

    public function setCompleted($isCompleted)
    {
        $this->isCompleted = $isCompleted;
        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    public function setUserId($user_id){
        $this->user_id = $user_id;
        return $this;
    }

    public function setCallbackUrl($callback_url){
        $this->callback_url = $callback_url;
        return $this;
    }

    public function setTriggerId($trigger_id){
        $this->trigger_id = $trigger_id;
        return $this;
    }

    public function setOriginalPrice($original_price)
    {
        $this->original_price = $original_price;
        return $this;
    }

    public function setOriginalCurrency($original_currency)
    {
        $this->original_currency = $original_currency;
        return $this;
    }

    public function setNotificationKey($notification_key)
    {
        $this->notification_key = $notification_key;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getAddressLabel()
    {
        return $this->addressLabel;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCompleted()
    {
        return $this->isCompleted;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getCallbackUrl()
    {
        return $this->callback_url;
    }

    public function getTriggerId()
    {
        return $this->trigger_id;
    }

    public function getOriginalPrice()
    {
        return $this->original_price;
    }

    public function getOriginalCurrency()
    {
        return $this->original_currency;
    }

    public function getNotificationKey()
    {
        return $this->notification_key;
    }

}