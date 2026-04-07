
<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>

<div class="header">

    <!-- LEFT SIDE: LMS NAME -->
    <div class="header-left">
        <h2>🎓 LMS</h2>
    </div>

    <!-- RIGHT SIDE: GREETING, TIME, DARK MODE, PROFILE -->
    <div class="header-right">

        <!-- GREETING + DATE/TIME -->
        <span id="greet"></span>
        <span id="date"></span>
        <span id="time"></span>

        <!-- DARK MODE BUTTON -->
        <button onclick="toggleDark()" class="dark-btn">🌙</button>

        <!-- PROFILE -->
        <div class="profile-box" onclick="toggleProfile()">
            👤 <?php echo $_SESSION['student_name'] ?? 'Student'; ?>
        </div>

        <!-- PROFILE DROPDOWN -->
        <div class="profile-dropdown" id="profileDropdown">
            <a href="profile.php">👤 My Profile</a>
            <a href="settings.php">⚙️ Settings</a>
            <a href="../logout.php">🚪 Logout</a>
        </div>

    </div>

</div>