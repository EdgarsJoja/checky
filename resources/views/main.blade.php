@extends('layout')

@section('content')
    @if (isset($options['authorized']) && $options['authorized']):
        <items-list
                :items="{{ json_encode($items) }}"
                :urls="{{ json_encode($urls) }}"
        ></items-list>
        <add-item
                :user="{{ json_encode($user) }}"
                :urls="{{ json_encode($urls) }}"
        ></add-item>
    @endif

    <notifications></notifications>
@endsection
