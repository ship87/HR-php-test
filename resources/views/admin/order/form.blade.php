@extends('layouts.app')
@section('content')
    <order-form inline-template :input="{{ json_encode($inputData) }}">
    <div class="row">
        <form action="{{ $action }}" method="post" class="form-horizontal" @submit.prevent="submitForm" ref="form">
            {{ csrf_field() }}

            <div class="form-group">
                <div class="col-md-4">
                    <label class="control-label" for="input1">email_клиента <span class="required-mark">*</span></label>
                    <input v-model="formData.client_email" v-validate="'required|email'" type="email"
                           class="form-control {{ $errors->has('client_email') ? 'error' : '' }}" id="input1" name="client_email">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4">
                    <label class="control-label" for="input1">партнер <span class="required-mark">*</span></label>
                    <select name="partner_id" v-model="formData.partner_id" v-validate="'required'"
                            class="form-control {{ $errors->has('partner_id') ? 'error' : '' }}">
                        <option disabled selected value>Выберите значение</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}">{{ $partner->name }}</option>
                        @endforEach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label">продукты</label>
                        </div>

                        <div class="col-md-6">
                            <selectize v-model="internal.product_selected" :settings="internal.selectize.settings">
                                <option disabled selected value>Выберите значение</option>
                                <template v-for="product in internal.product_src">
                                    <option :value="product.product_id">@{{ product.name }}</option>
                                </template>
                            </selectize>
                        </div>

                        <div class="col-md-2">
                            <button @click="addProduct()" type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                    <div class="row col-md-offset-1">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>название</th>
                                <th>кол-во</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <template v-for="orderProduct in formData.products.current">
                                <tr>
                                    <td>
                                        @{{ orderProduct.name }}
                                        <input type="hidden" v-model="orderProduct.id"
                                               :name="'products[current]['+orderProduct.id+'][id]'">
                                        <input type="hidden" v-model="orderProduct.product_id"
                                               :name="'products[current]['+orderProduct.id+'][product_id]'">
                                    </td>
                                    <td><input v-model="orderProduct.quantity"
                                               :name="'products[current]['+orderProduct.id+'][quantity]'" type="number"
                                               min="1" class="form-control"></td>
                                    <td>
                                        <button @click="removeProduct(formData.products.current, 'id', orderProduct.id)"
                                                type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        </button>
                                    </td>
                                </tr>
                            </template>

                            <template v-for="orderProduct in formData.products.for_add">
                                <tr>
                                    <td>
                                        @{{ orderProduct.name }}
                                        <input type="hidden" v-model="orderProduct.product_id"
                                               :name="'products[for_add]['+orderProduct.product_id+'][product_id]'">
                                    </td>
                                    <td><input v-model="orderProduct.quantity"
                                               :name="'products[for_add]['+orderProduct.product_id+'][quantity]'"
                                               type="number" min="1" class="form-control"></td>
                                    <td>
                                        <button @click="removeProduct(formData.products.for_add, 'product_id', orderProduct.product_id)"
                                                type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4">
                    <label class="control-label" for="input1">статус заказа <span class="required-mark">*</span></label>
                    <select name="status" v-model="formData.status" v-validate="'required'"
                            class="form-control {{ $errors->has('status') ? 'error' : '' }}">
                        <option disabled selected value>Выберите значение</option>
                        @foreach(\App\Common\Order\Models\Order::STATUS_DATA as $statusId => $data)
                            <option value="{{ $statusId }}">{{ $data[\App\Common\Order\Models\Order::ALIAS_TITLE] }}</option>
                        @endforEach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4">
                    <label class="control-label" for="input1">стоимость заказ</label>
                    <div class="alert alert-success" role="alert">@{{ sum }}</div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-4">
                    <input :disabled="!internal.isActiveForm" type="submit" value="сохранение изменений в заказе"
                           class="btn btn-default">
                </div>
            </div>
        </form>
    </div>
    </order-form>
@endsection