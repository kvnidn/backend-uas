<nav>

    <div class="tab-container">
        <div class="header">
            <img src="../assets/FTIUntar.png" alt="FTI Untar" height="60px">
        </div>

        <nav class="sidebar">
            @auth
            <p style="font-size: 14px;">Welcome back, </br>
                <span style="font-weight: 900; font-size: 20px;">{{ auth()->user()->name }}</span></p>
            @endauth

            <a class="{{ ($title === "Home") ? "active" : ""}}" href="/">Home</a>

            <a class="{{ ($title === "About") ? "active" : ""}}" href="/about">About</a>

            <a class="{{ ($title === "View") ? "active" : ""}}" href="/view">View</a>

            <a class="{{ ($title === "KeyLending") ? "active" : ""}}" href="/key-lending">Room Lending</a>

            @auth
                @if(auth()->user()->role == 'Admin')
                    <a class="{{ ($title === "Schedule") ? "active" : ""}}" href="/schedule">Schedule</a>

                    <a class="{{ ($title === "Assignment") ? "active" : ""}}" href="/assignment">Assignment</a>

                    <a class="{{ ($title === "Subject") ? "active" : ""}}"  href="/subject">Subject</a>

                    <a class="{{ ($title === "Room") ? "active" : "" }}"  href="/room">Room</a>

                    <a class="{{ ($title === "Class") ? "active" : "" }}"  href="/class">Class</a>

                    <a class="{{ ($title === "User") ? "active" : "" }}" href="/user">User</a>
                @elseif(in_array(auth()->user()->role, ['Lecturer', 'Assistant']))
                    <a class="{{ ($title === "Schedule") ? "active" : "" }}" href="/schedule">Schedule</a>
                @endif
            @else
                <a class="{{ ($title === "Login") ? "active" : "" }}" href="/login">Login</a>
            @endauth

            @auth
            <!-- <div class="logout">
                <form action="/logout" method="POST">
                    @csrf

                    <button type="submit" class="logout-button">
                        Logout
                    </button>
                </form>
            </div> -->

            <a class="logout-button{{ ($title === 'Logout') ? 'active' : '' }}" href="#" onclick="document.getElementById('logout-form').submit();"><i class="fa-solid fa-arrow-right-from-bracket" style="padding-right: 10px;"></i>Logout</a>
            
            <div class="logout">
                <form id="logout-form" action="/logout" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

            @endauth
        </nav>
    </div>
</nav>
