@extends('layouts.app')
@section('title', 'Edit task')
@section('content')<div class="page-heading"><div><h1>Edit task</h1><p>Update assignment, priority and progress.</p></div></div><section class="card"><form class="card-body" method="POST" action="{{ route('tasks.update', $task) }}">@csrf @method('PUT') @include('tasks._form', ['submitLabel' => 'Save changes'])</form></section>@endsection

