<nav>

    <div class="tab-container">
        <div class="header">
            <img src="../assets/FTIUntar.png" alt="FTI Untar" height="50px">
            @auth
            @if (auth()->user()->role != 'Admin')
            <a id="openEditProfileModal">Edit Profile <i class="fa-solid fa-pen-to-square"></i></a>
            @endif
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
                @endif
            @else
            @endauth

            @auth
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
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
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

@auth
<!-- Edit User Profile Modal -->
<div id="editProfileModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Edit User</h4>
        <form action="{{ url('user/'.auth()->user()->id.'/edit') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-name">
                <label>Name</label>
                <input type="text" name="name" value="{{  auth()->user()->name }}"/>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-email">
                <label>Email</label>
                <input type="text" name="email" value="{{  auth()->user()->email }}"/>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-password">
                <label>Current Password</label>
                <input type="password" name="password" value="" placeholder="required"/>
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                <br>
            </div>
            <div class="form-password">
                <label>New Password</label>
                <input type="password" name="new_password" value="" placeholder="optional"/>
                <br>
                @error('new_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-password">
                <label>Confirm Password</label>
                <input type="password" name="confirm_new_password" value="" placeholder="optional"/>
                @error('confirm_new_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-role">
                <label>Role</label>
                @if (auth()->user()->role == 'Lecturer')
                <input type="radio" name="role" value="Lecturer" {{ auth()->user()->role == 'Lecturer' ? 'checked': '' }}> Lecturer
                @elseif (auth()->user()->role == 'Assistant')
                <input type="radio" name="role" value="Assistant" {{ auth()->user()->role == 'Assistant' ? 'checked': '' }}> Assistant
                @else
                <input type="radio" name="role" value="Admin" {{ auth()->user()->role == 'Admin' ? 'checked': '' }}> Admin
                <input type="radio" name="role" value="Lecturer" {{ auth()->user()->role == 'Lecturer' ? 'checked': '' }}> Lecturer
                <input type="radio" name="role" value="Assistant" {{ auth()->user()->role == 'Assistant' ? 'checked': '' }}> Assistant
                @endif
                @error('role') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</div>
@endauth