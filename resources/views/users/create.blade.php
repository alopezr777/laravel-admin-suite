@extends('layouts.app')
@section('title', 'Add team member')
@section('content')<div class="page-heading"><div><h1>Add team member</h1><p>Create an account and assign its workspace role.</p></div></div><section class="card"><form class="card-body" method="POST" action="{{ route('users.store') }}">@csrf @include('users._form', ['submitLabel' => 'Create account'])</form></section>@endsection

