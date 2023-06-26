@extends('base')
@section('title', 'Games')
@section('body')
    <section class="w-100 h-100">
        @include('header')
        <div class="mt-2 container">
            <h3 class="mb-1">Games</h3>
            <table class="table w-100">
                <thead>
                <tr>
                    <th class="col-1">Ico</th>
                    <th class="col">Title</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($games as $game)

                    <tr>
                        <td></td>
                        <td><a href="{{route('game', ['slug'=>$game->slug])}}">{{$game->title}}</a></td>
                        <td>
                            @if($game->deleted_at)
                                <form action="{{route('refresh_game')}}" method="POST">
                                    @csrf
                                    <input type="submit" value="Refresh" class="btn btn-success">
                                </form>
                            @else
                                <form action="{{route('delete_game')}}" method="POST">
                                    @csrf
                                    <input type="submit" value="Delete" class="btn btn-danger">
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
