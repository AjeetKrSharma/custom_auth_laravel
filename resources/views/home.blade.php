@extends('layouts.app')
@section('content')
<h4>Home:{{Auth::user()->name}}</h4>
@endsection