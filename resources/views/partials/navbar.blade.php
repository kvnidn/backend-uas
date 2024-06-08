<nav>

    <div class="tab-container">
        <nav class="sidebar">
            @auth
            <p>Welcome Back, <span style="font-weight: 600;">{{ auth()->user()->name }}</span> !!</p>
            @endauth

            <a class="{{ ($title === "Home") ? "active" : ""}}" href="/">Home</a>

            <a class="{{ ($title === "About") ? "active" : ""}}" href="/about">About</a>

            @auth
                <a class="{{ ($title === "Schedule") ? "active" : ""}}" href="/schedule">Schedule</a>

                <a class="{{ ($title === "Assignment") ? "active" : ""}}" href="/assignment">Assignment</a>

                <a class="{{ ($title === "Subject") ? "active" : ""}}"  href="/subject">Subject</a>

                <a class="{{ ($title === "Room") ? "active" : "" }}"  href="/room">Room</a>

                <a class="{{ ($title === "User") ? "active" : "" }}" href="/user">User</a>
            @else
                <a class="{{ ($title === "User") ? "active" : "" }}" href="/user">User</a>
                <a class="{{ ($title === "Login") ? "active" : "" }}" href="/login">Login</a>
            @endauth

            @auth
            <div class="logout">
                <form action="/logout" method="POST">
                    @csrf

                    <button type="submit" class="logout-button">
                        Logout
                    </button>
                </form>
            </div>
            @endauth
        </nav>
    </div>
</nav>
