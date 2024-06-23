<nav>

    <div class="tab-container">
        <div class="header">
            <img src="../assets/FTIUntar.png" alt="FTI Untar" height="50px">
            @auth
            <p style="font-size: 14px;" class="greetings">Welcome back, </br>
                <span style="font-weight: 900; font-size: 20px;">{{ auth()->user()->name }}</span></p>
            @else
                <a class="{{ ($title === "Login") ? "active" : "" }}" id="openLoginModal">Login</a>
            @endauth
        </div>

        <nav class="sidebar">
            @auth
            <p style="font-size: 14px;" class="greetings">Welcome back, </br>
                <span style="font-weight: 900; font-size: 20px;">{{ auth()->user()->name }}</span></p>
            @endauth
        </div>

        <nav class="sidebar">

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
                    <a class="{{ ($title === "User") ? "active" : "" }}" href="/user">User</a>
                @endif
            @else
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

<!-- Login User Modal -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Login User</h4>
        <form action="{{ url('login') }}" method="POST">
            @csrf
            @method('POST')

            <div class="form-name">
                <label>Email</label>
                <input type="text" name="email" value="{{  old('email') }}" autofocus/>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-password">
                <label>Password</label>
                <input type="password" name="password" value="{{  old('password') }}"/>
                @error('password') <span class="">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember Me
                    </label>
                </div>
            </div>
            <div class="save-user-button">
                <button type="submit">Login</button>
            </div>
        </form>
    </div>
</div>