@extends('layouts.app')

@section('content')
    <router-view user="{{auth()->user()}}" secondary_users="{{auth()->user()->admins}}" apps="{{json_encode(config('app_data.apps'))}}"></router-view>
@endsection
