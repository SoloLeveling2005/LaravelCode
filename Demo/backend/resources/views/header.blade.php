<header class="w-100 bg-dark py-3 text-white">
    <div class="container d-flex justify-content-between">
        <div>
            <a class="me-2 text-white" href="{{route('admins')}}">Admins</a>
            <a class="me-2 text-white" href="{{route('users')}}">Users</a>
            <a class="me-2 text-white" href="{{route('games')}}">Games</a>
        </div>
        <form action="{{route('logout')}}" method="POST">
            @csrf
            <input type="submit" value="Logout">
        </form>
    </div>
</header>
