@extends('layouts.app')

@section('content')
    <router-view user="{{auth()->user()}}" apps="{{json_encode(config('applist.apps'))}}"></router-view>
@endsection
