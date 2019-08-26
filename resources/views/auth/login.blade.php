@extends('layouts.app')

@section('content')
<div class="card mx-auto md:w-1/2 rounded">
    <div class="text-2xl text-center my-6">{{ __('Login') }}</div>

    <div class="mx-10 mb-10">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="w-full mb-6">
                <label for="email" class="my-2">{{ __('E-Mail Address') }}</label>

                <div class="my-2">
                    <input id="email" type="email" class="w-full rounded border h-8 @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="text-red-500 text-sm" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="w-full mb-6">
                <label for="password" class="my-2">{{ __('Password') }}</label>

                <div class="my-2">
                    <input id="password" type="password" class="w-full rounded border h-8 @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="text-red-500 text-sm" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="my-6">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                <label class="form-check-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>

            <div>
                <button type="submit" class="button mr-3">
                    {{ __('Login') }}
                </button>

                @if (Route::has('password.request'))
                    <a class="text-gray-600 underline" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
