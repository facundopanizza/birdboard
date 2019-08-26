@extends('layouts.app')

@section('content')
<div class="card mx-auto md:w-1/2 rounded">
    <div class="text-2xl text-center my-6">{{ __('Register') }}</div>

    <div class="mx-10 mb-10">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="w-full mb-6">
                <label for="name" class="my-2">{{ __('Name') }}</label>

                <div class="my-2">
                    <input id="name" type="text" class="w-full rounded border h-8 @error('name') border-red-500 @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                    @error('name')
                        <span class="text-red-500 text-sm" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="w-full mb-6">
                <label for="email" class="my-2">{{ __('E-Mail Address') }}</label>

                <div class="my-2">
                    <input id="email" type="email" class="w-full rounded border h-8 @error('email') text-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                    @error('email')
                        <span class="text-red-500 text-sm" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="my-6">
                <label for="password" class="my-2">{{ __('Password') }}</label>

                <div class="my-2">
                    <input id="password" type="password" class="w-full border h-8 @error('password') border-red-500 @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                        <span class="text-red-500 text-sm" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="my-6">
                <label for="password-confirm" class="my-2">{{ __('Confirm Password') }}</label>

                <div class="my-2">
                    <input id="password-confirm" type="password" class="w-full rounded border h-8" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>

            <div>
                <button type="submit" class="button">
                    {{ __('Register') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
