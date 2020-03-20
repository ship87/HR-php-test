<?php
/**
 * @var $order \App\Common\Order\Models\Order
 */
?>

<h1>заказ №{{ $order->id }} завершен</h1>

<p>состав заказа: {{ $order->getTitleProducts() }}</p>

<p>стоимость заказа: {{ $order->getSum() }}</p>