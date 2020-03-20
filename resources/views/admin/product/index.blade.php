<?php

use Illuminate\Support\Collection;

/**
 * @var $products Collection
 */
?>

@extends('layouts.app')
@section('content')

    @include('admin.product.table', ['products' => $products])

@endsection