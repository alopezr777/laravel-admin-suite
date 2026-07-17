@extends('layouts.app')
@section('title', 'Edit project')
@section('content')
    <div class="page-heading"><div><h1>Edit project</h1><p>Update scope, ownership and delivery information.</p></div></div>
    <section class="card"><form class="card-body" method="POST" action="{{ route('projects.update', $project) }}">@csrf @method('PUT') @include('projects._form', ['submitLabel' => 'Save changes'])</form></section>
@endsection

