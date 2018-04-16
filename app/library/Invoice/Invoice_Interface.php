<?php
/**
 * Created by PhpStorm.
 * User: vladislavdavarasvili
 * Date: 13/04/2018
 * Time: 13:30
 */

namespace Library\Invoice;


interface Invoice_Interface
{

    public function create(InvoiceModel $invoice_model): array;

    public function get(int $invoice_id): array;

    public function update(InvoiceModel $invoice_model);

    public function getRate(string $currency): array;

    public function subscribeToAddress(InvoiceModel $invoice_model): array;

    public function getBalance(InvoiceModel $invoice_model);

    public function getWalletBalance();
}