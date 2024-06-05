<nav>
    <div class="">
        <nav class="sidebar">
            <a class="{{ ($title === "Home") ? "active" : ""}}" href="/">Home</a>

            <a class="{{ ($title === "About") ? "active" : ""}}" href="/about">About</a>

            <a class="{{ ($title === "Admin") ? "active" : ""}}"  href="/admin">Admin</a>

            <a class="{{ ($title === "User") ? "active" : ""}}" href="/user">User</a>
        </nav>
    </div>
</nav>
