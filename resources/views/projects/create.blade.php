@extends('layouts.app')
@section('title', 'Create project')
@section('content')
    <div class="page-heading"><div><h1>Create project</h1><p>Add a new client initiative to the delivery portfolio.</p></div></div>
    <section class="card"><form class="card-body" method="POST" action="{{ route('projects.store') }}">@csrf @include('projects._form', ['submitLabel' => 'Create project'])</form></section>
@endsection

