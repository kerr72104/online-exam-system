@extends('layouts.app')
@section('title', 'Student Dashboard')
@section('content')
    Logged in
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Log Out</button>
    </form>
@endsection