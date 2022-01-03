@extends('layouts.app')

@section('content')
    @if(Auth::user()->user_type == 'admin')
        <router-view user="{{Auth::user()}}" secondary_users="{{auth()->user()->admins}}" apps="{{json_encode(config('app_data.apps'))}}"></router-view>
    @else
        @php
        $user = Auth::user();
        $user->subscription_list = Auth::user()->owner->subscription_list;
        @endphp
        <router-view user="{{$user}}" secondary_users="[]" apps="{{json_encode(config('app_data.apps'))}}"></router-view>
    @endif
@endsection
