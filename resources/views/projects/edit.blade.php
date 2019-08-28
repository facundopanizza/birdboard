@extends('layouts.app')

@section('content')
    <h1>Edit Your Project</h1>

    <form method="post" action="{{ $project->path() }}">
        @csrf
        @method('PATCH')
        @include('projects._form', ['buttonText' => 'Update Project'])
@endsection