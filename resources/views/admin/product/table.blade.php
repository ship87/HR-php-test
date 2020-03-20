<table class="table table-hover"
       id="products-table-module"
       data-csrf-token="{{ csrf_token() }}"
       data-url-api-update-price="{{ route('admin.product.update-price') }}"
>
    <thead>
    <tr>
        <th>ид_продукта</th>
        <th>наименование_продукта</th>
        <th>наименование_поставщика</th>
        <th>цена</th>
    </tr>
    </thead>
    <tbody>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->vendor->name }}</td>
            <td><a href="#" class="price-edit" data-id="text" data-pk="{{ $product->id }}" data-name="price">{{ $product->price }}</a></td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="row">
    {{ $products->links() }}
</div>

