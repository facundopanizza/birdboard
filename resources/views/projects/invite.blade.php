<div class="card flex flex-col mt-3">
    <h3 class="font-normal text-xl py-4 -ml-5 mb-3 border-l-4 border-blue-secondary pl-4">
        Inviate a User
    </h3>

    <form method="POST" action="{{ $project->path() . '/invitations' }}">
        @csrf

        <div class="mb-3">
            <input type="email" name="email" placeholder="Email Addres" class="w-full rounded border h-8 @error('email') border-red-500 @enderror">
        </div>

        <button type="submit" class="button">Invite</button>
    </form>
</div>