<?php
/**
 * Collection of utility functions used across the application
 */

/**
 * Sanitize input data
 * @param string $data Input data to sanitize
 * @return string Sanitized data
 */
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Format date to a readable format
 * @param string $date Date string
 * @param string $format Desired format (default: 'Y-m-d')
 * @return string Formatted date
 */
function formatDate($date, $format = 'Y-m-d') {
    return date($format, strtotime($date));
}

/**
 * Format currency amount
 * @param float $amount Amount to format
 * @param string $currency Currency code (default: 'PHP')
 * @return string Formatted currency amount
 */
function formatCurrency($amount, $currency = 'PHP') {
    return number_format($amount, 2) . ' ' . $currency;
}

/**
 * Generate a random string
 * @param int $length Length of the string (default: 10)
 * @return string Random string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

/**
 * Check if a string is a valid date
 * @param string $date Date string to validate
 * @param string $format Expected format (default: 'Y-m-d')
 * @return bool True if valid date, false otherwise
 */
function isValidDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Validate email address
 * @param string $email Email address to validate
 * @return bool True if valid email, false otherwise
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Clean file name to make it safe for storage
 * @param string $filename Original filename
 * @return string Sanitized filename
 */
function sanitizeFileName($filename) {
    // Remove any path components
    $filename = basename($filename);
    // Replace any non-alphanumeric characters except dots and dashes
    $filename = preg_replace('/[^a-zA-Z0-9.-]/', '_', $filename);
    return $filename;
}

/**
 * Format phone number
 * @param string $phone Phone number to format
 * @return string Formatted phone number
 */
function formatPhoneNumber($phone) {
    // Remove any non-numeric characters
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    // Format based on length
    $length = strlen($phone);
    if ($length == 11) {
        return substr($phone, 0, 4) . '-' . substr($phone, 4, 3) . '-' . substr($phone, 7);
    } elseif ($length == 10) {
        return substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6);
    }
    
    return $phone;
}

/**
 * Calculate age from birthdate
 * @param string $birthdate Birthdate in Y-m-d format
 * @return int Age
 */
function calculateAge($birthdate) {
    $birth = new DateTime($birthdate);
    $today = new DateTime('today');
    $age = $birth->diff($today)->y;
    return $age;
}

/**
 * Format file size in human readable format
 * @param int $bytes Size in bytes
 * @return string Formatted size
 */
function formatFileSize($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, 2) . ' ' . $units[$pow];
} 