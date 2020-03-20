@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-2">
            <div class="alert alert-success" role="alert">
                @if($temperature === null)
                    Ошибка получения сведений о температуре
                @else
                    {{ $temperature  > 0 ? '+' : '-'}} {{ $temperature }} &deg;C
                @endif
            </div>
        </div>
    </div>
@endsection