@extends('layouts.app')
@section('title', 'Edit team member')
@section('content')<div class="page-heading"><div><h1>Edit team member</h1><p>Update profile information, role and access status.</p></div></div><section class="card"><form class="card-body" method="POST" action="{{ route('users.update', $user) }}">@csrf @method('PUT') @include('users._form', ['submitLabel' => 'Save changes'])</form></section>@endsection

