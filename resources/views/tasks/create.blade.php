@extends('layouts.app')
@section('title', 'Create task')
@section('content')<div class="page-heading"><div><h1>Create task</h1><p>Add a new deliverable and assign responsibility.</p></div></div><section class="card"><form class="card-body" method="POST" action="{{ route('tasks.store') }}">@csrf @include('tasks._form', ['submitLabel' => 'Create task'])</form></section>@endsection

