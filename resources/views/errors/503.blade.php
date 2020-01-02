@extends('layouts.error')

@section('title', 'Service Unavailable')
@section('code', '503')
@section('message', $exception->getMessage() ?: 'Service Unavailable')
