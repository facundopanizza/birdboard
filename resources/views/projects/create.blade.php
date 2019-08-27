@extends('layouts.app')

@section('content')
<h1>Create a Project</h1>

<form method="post" action="/projects">
    @csrf

    <div>
        <label for="title" style="display: block">Name</label>
        <input type="text" name="title">
    </div>
    <div>
        <label for="description" style="display: block">Description</label>
        <textarea name="description" cols="30" rows="10"></textarea>
    </div>
    <input class="button" type="submit" value="Create Project">
    <a href="/projects">Back</a>
</form>
@endsection