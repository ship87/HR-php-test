<?php

namespace App\Common\Order\Mail;

use App\Common\Order\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Class OrderCompletedMail - отправка письма о завершенном заказе
 * @package App\Common\Order\Mail
 */
class OrderCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Заказ
     *
     * @var Order
     */
    protected $order;

    /**
     * Получатели письма
     *
     * @var array
     */
    protected $recipients;

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * @param array $recipients
     */
    public function setRecipients(array $recipients)
    {
        $this->recipients = $recipients;
    }

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('заказ №' . $this->order->id . ' завершен')
            ->to($this->recipients)
            ->view('mails.order.order-completed', [
                'order' => $this->order
            ]);
    }
}
