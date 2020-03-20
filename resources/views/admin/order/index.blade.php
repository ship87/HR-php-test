<?php

use Illuminate\Support\Collection;

/**
 * @var $currentActiveTab int
 * @var $ordersCurrent Collection
 * @var $ordersPastDue Collection
 * @var $ordersNewOrders Collection
 * @var $ordersCompletedOrders Collection
 */
?>

@extends('layouts.app')
@section('content')

    <ul class="nav nav-tabs">
        <li class="{{ $currentActiveTab === \App\Common\Order\Models\Order::CURRENT_ORDERS ? 'active' : '' }}">
            <a data-toggle="tab" href="#current">Текущие</a>
        </li>
        <li class="{{ $currentActiveTab === \App\Common\Order\Models\Order::PAST_DUE_ORDERS ? 'active' : '' }}">
            <a data-toggle="tab" href="#past-due">Просроченные</a>
        </li>
        <li class="{{ $currentActiveTab === \App\Common\Order\Models\Order::NEW_ORDERS ? 'active' : '' }}">
            <a data-toggle="tab" href="#new">Новые</a>
        </li>
        <li class="{{ $currentActiveTab === \App\Common\Order\Models\Order::COMPLETED_ORDERS ? 'active' : '' }}">
            <a data-toggle="tab" href="#completed">Выполненные</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane {{ $currentActiveTab === \App\Common\Order\Models\Order::CURRENT_ORDERS ? 'active' : '' }}"
             id="current">
            <div class="row">
                <div class="col-md-12">
                    @include('admin.order.table', ['orders' => $ordersCurrent])
                </div>
            </div>
        </div>
        <div class="tab-pane {{ $currentActiveTab === \App\Common\Order\Models\Order::PAST_DUE_ORDERS ? 'active' : '' }}"
             id="past-due">
            <div class="row">
                <div class="col-md-12">
                    @include('admin.order.table', ['orders' => $ordersPastDue])
                </div>
            </div>
        </div>
        <div class="tab-pane {{ $currentActiveTab === \App\Common\Order\Models\Order::NEW_ORDERS ? 'active' : '' }}"
             id="new">

            <div class="row">
                <div class="col-md-12">
                    @include('admin.order.table', ['orders' => $ordersNew])
                </div>
            </div>
        </div>
        <div class="tab-pane {{ $currentActiveTab === \App\Common\Order\Models\Order::COMPLETED_ORDERS ? 'active' : '' }}"
             id="completed">
            <div class="row">
                <div class="col-md-12">
                    @include('admin.order.table', ['orders' => $ordersCompleted])
                </div>
            </div>
        </div>
    </div>

@endsection