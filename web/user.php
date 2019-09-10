<?php
$user = GetCurrentUser();

if($user !== null) {
    if($user->isSuperAdmin() || $user->isAdmin())
        require_once standart_path.'web/user_admin.php';
    else require_once standart_path.'web/user_non_admin.php';
}