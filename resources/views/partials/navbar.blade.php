<nav>

    <div class="tab-container">
        <div class="header">
            <img src="../assets/FTIUntarWhite.png" alt="FTI Untar" height="50px">
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

            <a class="{{ ($title === "Home") ? "active" : ""}}" href="/"><i class="fa-solid fa-house"></i>Home</a>

            <a class="{{ ($title === "About") ? "active" : ""}}" href="/about"><i class="fa-solid fa-circle-info"></i>About</a>

            <a class="{{ ($title === "View") ? "active" : ""}}" href="/view"><i class="fa-solid fa-eye"></i>View</a>

            <a class="{{ ($title === "KeyLending") ? "active" : ""}}" href="/key-lending"><i class="fa-solid fa-key"></i>Room Lending</a>

            @auth
                @if(auth()->user()->role == 'Admin')
                    <a class="{{ ($title === "Schedule") ? "active" : ""}}" href="/schedule"><i class="fa-solid fa-calendar-days"></i>Schedule</a>

                    <a class="{{ ($title === "Assignment") ? "active" : ""}}" href="/assignment"><i class="fa-solid fa-list-check"></i>Assignment</a>

                    <a class="{{ ($title === "Subject") ? "active" : ""}}"  href="/subject"><i class="fa-solid fa-clipboard-list" style="padding-left: 2px; padding-right: 18px;"></i>Subject</a>

                    <a class="{{ ($title === "Room") ? "active" : "" }}"  href="/room"><i class="fa-solid fa-chalkboard"></i>Room</a>

                    <a class="{{ ($title === "Class") ? "active" : "" }}"  href="/class"><i class="fa-solid fa-tags"></i>Class</a>

                    <a class="{{ ($title === "User") ? "active" : "" }}" href="/user"><i class="fa-solid fa-users"></i>User</a>
                @elseif(in_array(auth()->user()->role, ['Lecturer', 'Assistant']))
                    <a class="{{ ($title === "Schedule") ? "active" : "" }}" href="/schedule"><i class="fa-solid fa-calendar-days"></i>Schedule</a>
                @endif
            @else
            @endauth

            @auth
            <a class="logout-button{{ ($title === 'Logout') ? 'active' : '' }}" href="#" onclick="document.getElementById('logout-form').submit();"><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</a>

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
<div id="loginModal" class="modal {{ $errors->login->any() || session('loginError') ? 'open' : ''}}">
    <div class="modal-content">
        <h4>Login User</h4>
        <form action="{{ url('login') }}" method="POST">
            @csrf
            @method('POST')

            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" id="email" value="{{old('email')}}" data-old-value="{{ old('email') }}" autofocus/>
                <br>
                <!-- @error('email', 'login') <span class="text-danger">{{ $message }}</span> @enderror -->
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="password" value=""/>
                <br>
                <!-- @error('password', 'login') <span class="text-danger">{{ $message }}</span> @enderror -->
            </div>

            <div class="form-remember">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label" for="remember">
                        Remember Me
                    </label>
                </div>

                <!-- @if (session('loginError'))
                <span class="text-danger">
                    {{ session('loginError') }}
                </span>
                @endif -->
            </div>

            @if ($errors->login->any() || session('loginError'))
                <div class="form-errors">
                    @error('email', 'login') 
                        @if ($message)
                            <span class="text-danger">
                                {{ $message }}
                            </span> <br>
                        @endif
                    @enderror

                    @error('password', 'login')
                        @if ($message) 
                            <span class="text-danger">
                                {{ $message }}
                            </span>
                        @endif 
                    @enderror
                    
                    @if (session('loginError'))
                        <span class="text-danger">
                            {{ session('loginError') }}
                        </span>
                    @endif
                </div>
            @endif

            <div class="form-actions">
                <div class="close-user-button">
                    <span class="close"><i class="fa-solid fa-xmark"></i>Close</</span>
                </div>
                <div class="save-user-button">
                    <button type="submit"><i class="fa-solid fa-check"></i>Login</button>
                </div>
            </div>
        </form>
    </div>
</div>

@auth
<!-- Edit User Profile Modal -->
<div id="editProfileModal" class="modal {{ $errors->editProfile->any() ? 'open' : '' }}">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h4>Edit User</h4>
        <form action="{{ url('user/'.auth()->user()->id.'/editProfile') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-name">
                <label>Name</label>
                <input type="text" name="name" id="modalProfileName" value="{{  auth()->user()->name }}" data-old-value="{{ old('name') }}"/>
                <br>
                @error('name', 'editProfile') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-email">
                <label>Email</label>
                <input type="text" name="email" id="modalProfileEmail" value="{{  auth()->user()->email }}" data-old-value="{{ old('email') }}"/>
                <br>
                @error('email', 'editProfile') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-password">
                <label>Current Password</label>
                <input type="password" name="password" value="" placeholder="required"/>
                <br>
                @error('password', 'editProfile') <span class="text-danger">{{ $message }}</span> @enderror
                <br>
            </div>
            <div class="form-password">
                <label>New Password</label>
                <input type="password" name="new_password" value="" placeholder="optional"/>
                <br>
                @error('new_password', 'editProfile') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-password">
                <label>Confirm Password</label>
                <input type="password" name="confirm_new_password" value="" placeholder="optional"/>
                <br>
                @error('confirm_new_password', 'editProfile') <span class="text-danger">{{ $message }}</span> @enderror
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
                <br>
                @error('role') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="save-user-button">
                <button type="submit">Update</button>
            </div>
        </form>
    </div>
</div>
@endauth
