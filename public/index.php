<?php
session_start();

if (!isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit;
}

switch ($_SESSION['user_role']) {
    case 'admin':
        header("Location: dashboard_admin.php");
        break;
    case 'manager':
        header("Location: dashboard_manager.php");
        break;
    case 'staff':
        header("Location: dashboard_staff.php");
        break;
    default:
        session_destroy();
        header("Location: login.php");
        break;
}
exit;
