@extends('base')
@section('title', 'Admins')
@section('body')
    <section class="h-100">
        @include('header')
        <div class="mt-2 container">
            <h3 class="mb-1">Admins</h3>
            <table class="w-100 table">
                <thead>
                    <tr>
                        <th class="col">Username</th>
                        <th class="col">Updated at</th>
                        <th class="col">Created at</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($admins as $admin)
                    <tr>
                        <td>{{$admin->username}}</td>
                        <td>{{$admin->updated_at}}</td>
                        <td>{{$admin->created_at}}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </section>
@endsection
