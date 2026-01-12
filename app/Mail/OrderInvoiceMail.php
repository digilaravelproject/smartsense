<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $customer;
    public $items;

    public function __construct($order, $customer, $items)
    {
        $this->order = $order;
        $this->customer = $customer;
        $this->items = $items;
    }

    public function build()
    {
        return $this->subject('Invoice - Order #' . $this->order->id)
            ->view('email-templates.order-invoice');
    }
}
