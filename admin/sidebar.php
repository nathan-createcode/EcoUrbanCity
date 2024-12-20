<?php
$sidebar = '
<aside class="sidebar">
    <div class="logo">
        <i class="fas fa-city"></i>
        <span>EcoUrbanCity</span>
    </div>

    <!-- Search Container -->
<div class="search-container">
    <i class="fas fa-search"></i>
    <input type="text" placeholder="Search menu..." id="sidebarSearch">
</div>

    <nav class="nav-menu">
        <a href="admin.php" class="nav-link">
            <i class="fas fa-tachometer-alt"></i>
            Dashboard
        </a>
        <a href="read_government.php" class="nav-link">
            <i class="fas fa-users"></i>
            Government
        </a>
        <!-- Update this link to ensure correct path -->
        <a href="./read_admin.php" class="nav-link">
            <i class="fas fa-user-shield"></i>
            Admin
        </a>
        <a href="#" class="nav-link">
            <i class="fas fa-cog"></i>
            Settings
        </a>
        <a href="../Login_adgov/logout_adgov.php" class="nav-link">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </a>
    </nav>
</aside>

<script>
document.getElementById("sidebarSearch").addEventListener("keyup", function() {
    let searchText = this.value.toLowerCase();
    let menuItems = document.querySelectorAll(".nav-link");

    menuItems.forEach(item => {
        let text = item.textContent.toLowerCase();
        if(text.includes(searchText)) {
            item.style.display = "";
        } else {
            item.style.display = "none";
        }
    });
});
</script>
';
?>