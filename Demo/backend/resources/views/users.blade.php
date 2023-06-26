@extends('base')
@section('title', 'Users')
@section('body')
    <section class="w-100 h-100">
        @include('header')
        <div class="mt-2 container">
            <h3 class="mb-1">Users</h3>
            <table class="table w-100">
                <thead>
                <tr>
                    <th class="col">Username</th>
                    <th class="col">Updated at</th>
                    <th class="col">Created at</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)

                    <tr>
                        <td></td>
                        <td><a href="{{route('user', ['username'=>$user->username])}}">{{$user->username}}</a></td>
                        <td>
                            @if($user->is_ban)
                                <form action="{{route('unban')}}" method="POST">
                                    @csrf
                                    <input type="submit" value="" class="btn btn-success">
                                </form>
                            @else
                                <form action="{{route('ban')}}" method="POST">
                                    @csrf
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            Действие
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><form class="dropdown-item" method="POST">
                                                    <input type="submit" value="Spamming">
                                                </form></li>
                                            <li><form class="dropdown-item" method="POST">
                                                    <input type="submit" value="Cheating">
                                                </form></li>
                                            <li><form class="dropdown-item" method="POST">
                                                    <input type="submit" value="Other">
                                                </form></li>
                                        </ul>
                                    </div>
                                </form>
                            @endif

                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </section>
@endsection
