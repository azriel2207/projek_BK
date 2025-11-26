@extends('layouts.app')

@section('title', 'Guru BK')

@section('content')
    {{-- Jika view guru sekarang menggunakan @section('content') maka akan masuk di sini --}}
    @yield('content')
@endsection