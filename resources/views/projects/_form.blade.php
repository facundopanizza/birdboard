    @csrf
    <div>
        <label for="title" style="display: block">Name</label>
        <input type="text" name="title" value="{{ $project->title }}" required>
    </div>
    <div>
        <label for="description" style="display: block">Description</label>
        <textarea name="description" cols="30" rows="10" required>{{ $project->description }}</textarea>
    </div>
    <input class="button" type="submit" value="{{ $buttonText }}">

    <a href="{{ $project->path() }}">Cancel</a>

    @if($errors->any())
        <div class="text-red-500 text-sm mt-4">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
</form>