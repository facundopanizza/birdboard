@extends('layouts.app')

@section('content')
    <h1>Let's start something new</h1>

    <form method="post" action="/projects">
        @include('projects._form', [
            'project' => new App\Project,
            'buttonText' => 'Create Project'
        ])
@endsection