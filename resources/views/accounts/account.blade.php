@extends('layouts.bootstrap')
@section('content')
    {{--
        style block in a PHP file? what year is it?!
        anywho, currently only including the default bootstrap css. didn't want to add an new extra file just for this...
        so here's the classic:
        TODO: move this to an external file
    --}}
    <style>
        #pocketcasts-account li::marker {
            font-weight: bold;
        }
    </style>
    <div class="row">
        <div class="col-sm-6">
            <h2>{{ $account->name }}</h2>
            <form action="{{ route('accounts.update', $account) }}" method="POST">
                @method('PUT')
                @csrf
                @if($account->edit_username)
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control @if($errors->has('username')) is-invalid @endif" id="username" name="username" value="{{ old('username') }}" />
                    </div>
                @endif
                @if($account->edit_token)
                    <div class="form-group">
                        <label for="token">Token</label>
                        <input type="text" class="form-control @if($errors->has('token')) is-invalid @endif" id="token" name="token" value="{{ old('token') }}" />
                    </div>
                @endif
                <p class="pt-3"><input type="submit" value="Submit" class="btn btn-primary" /></p>
            </form>
        </div>
        @if($account->slug === 'pocketcasts')
            <div class="col-sm-6" id="pocketcasts-account">
                <p>To communitate with <a href="https://pocketcasts.com/" target="_blank">PocketCasts</a> we need an access token for your account. </p>
                <ol>
                    <li>Open command prompt or terimal on your computer or you may use something like <a href="https://httpie.io/app" target="_blank">HTTPie</a>.
                        <br />
                        <div class="alert alert-warning" role="alert">
                            <small>If you're not familar with command prompt or terminal please use HTTPie. Pasting commands into those apps on your computer without understanding them is a security risks and could cause loss of data.</small>
                        </div>
                    </li>
                    <li>
                        Enter your email and password in the form below and click "Update CURL Command". You should see your email and password inside the quotes above the form.
                        <div class="alert alert-info" role="alert">
                            This is completley optional. Your email & password entered is not sent or saved; it's only to make copy and pasting the curl command easier.
                        </div>
                    </li>
                    <li>Paste the command below into your app from step 1. If you're using HTTPie click "Import" in the bottom left and choose "Text" before pasting and importing.</li>
                    <li>
                        Copy the access token and past it into the box on the left.
                        <div class="alert alert-warning" role="alert">
                            Do not share this anywhere else. Anyone with this code would be able to make changes to your PocketCasts account. When & What will only use it for looking up your listening history.
                        </div>
                    </li>
                </ol>

                <div>
                    curl -H  "Content-Type: application/json" -H "Accept: application/json" -d '{"email": "<span id="pocketcasts-email"></span>", "password":"<span id="pocketcasts-password"></span>"}' https://api.pocketcasts.com/user/login
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" />
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" />
                </div>
                <p class="pt-3"><button id="generate-token" class="btn btn-primary">Update CURL command</button></p>
            </div>
        @endif
    </div>
@endsection
