<table class="table table-hover">
    <thead>
    <tr>
        <th>ид_заказа</th>
        <th>название_партнера</th>
        <th>стоимость_заказа</th>
        <th>наименование_состав_заказа</th>
        <th>статус_заказа</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td><a href="{{ route('admin.order.form', ['orderId' => $order->id]) }}">{{ $order->id }}</a></td>
            <td>{{ $order->partner->name }}</td>
            <td>{{ $order->getSum() }}</td>
            <td>{{ $order->getTitleProducts() }}</td>
            <td>{{ $order->getStatusTitle() }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

@if($orders instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div class="row">
    {{ $orders->links() }}
</div>
@endif
