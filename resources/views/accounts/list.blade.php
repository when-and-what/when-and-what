@extends('layouts.bootstrap')
@section('content')
    <div class="d-flex w-100 justify-content-between pb-3">
        <h1>Accounts</h1>
    </div>
    <ul class="list-group list-group-horizontal d-flex w-100">
        @foreach($accounts as $account)
            <li class="list-group-item w-full">
                <h3>
                    {{ $account->name }}
                </h3>
                @if(count($account->users) > 0)
                    <form action="{{ route('accounts.destroy', $account) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn">✅️</button>
                    </form>
                @else
                    <a href="{{ route('accounts.show', $account) }}">✖️</a>
                @endif
            </li>
        @endforeach
    </ul>
@endsection
