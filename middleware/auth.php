<?php
session_start();

function authorize($allowed_roles = []) {
    if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], $allowed_roles)) {
        header('Location: ../unauthorized.php');
        exit;
    }
}
