<?php
/**
 * HELPERS.PHP - Các Hàm Hỗ Trợ Chung
 * Tập hợp các function tiện ích được sử dụng trong ứng dụng
 */

/**
 * Kiểm tra request method
 */
function isGet() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function isPut() {
    return $_SERVER['REQUEST_METHOD'] === 'PUT';
}

function isDelete() {
    return $_SERVER['REQUEST_METHOD'] === 'DELETE';
}

/**
 * Kiểm tra Ajax request
 */
function isAjax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
           $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

/**
 * Flash message
 */
function setFlashMessage($type, $message) {
    $_SESSION[$type] = $message;
}

function getFlashMessage($type) {
    if (isset($_SESSION[$type])) {
        $message = $_SESSION[$type];
        unset($_SESSION[$type]);
        return $message;
    }
    return null;
}

function hasFlashMessage($type) {
    return isset($_SESSION[$type]);
}

/**
 * Kiểm tra input POST
 */
function hasPost($key) {
    return isset($_POST[$key]);
}

function getPost($key, $default = null) {
    return $_POST[$key] ?? $default;
}

/**
 * Kiểm tra input GET
 */
function hasGet($key) {
    return isset($_GET[$key]);
}

function getGet($key, $default = null) {
    return $_GET[$key] ?? $default;
}

/**
 * Kiểm tra input SESSION
 */
function hasSession($key) {
    return isset($_SESSION[$key]);
}

function getSession($key, $default = null) {
    return $_SESSION[$key] ?? $default;
}

function setSession($key, $value) {
    $_SESSION[$key] = $value;
}

function removeSession($key) {
    unset($_SESSION[$key]);
}

/**
 * Kiểm tra file được upload
 */
function hasFile($name) {
    return isset($_FILES[$name]) && $_FILES[$name]['error'] === UPLOAD_ERR_OK;
}

function getFile($name) {
    return $_FILES[$name] ?? null;
}

/**
 * Validate file upload
 */
function validateUploadFile($file, $maxSize = 5242880, $allowedTypes = []) {
    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    if ($file['size'] > $maxSize) {
        return false;
    }
    
    if (!empty($allowedTypes) && !in_array($file['type'], $allowedTypes)) {
        return false;
    }
    
    return true;
}

/**
 * Safe redirect
 */
function redirect($url) {
    if (!headers_sent()) {
        header("Location: $url");
        exit;
    }
}

/**
 * Get previous URL
 */
function getPreviousUrl() {
    return $_SERVER['HTTP_REFERER'] ?? '?page=home';
}

/**
 * Redirect back with message
 */
function redirectBack($message, $type = 'success') {
    setFlashMessage($type, $message);
    redirect(getPreviousUrl());
}

/**
 * Require login
 */
function requireLogin() {
    if (!isLoggedIn()) {
        setFlashMessage('error', 'Vui lòng đăng nhập trước');
        redirect('?page=login');
    }
}

/**
 * Require admin
 */
function requireAdmin() {
    requireLogin();
    
    if (!isAdmin()) {
        setFlashMessage('error', 'Bạn không có quyền truy cập');
        redirect('?page=home');
    }
}

/**
 * Check if value exists in array
 */
function inArray($value, $array, $strict = false) {
    return in_array($value, $array, $strict);
}

/**
 * Array filter
 */
function arrayFilter($array, $key, $value) {
    return array_filter($array, function($item) use ($key, $value) {
        return isset($item[$key]) && $item[$key] == $value;
    });
}

/**
 * Convert array to select options
 */
function arrayToOptions($array, $valueKey = 'id', $textKey = 'name', $selected = null) {
    $options = '';
    
    foreach ($array as $item) {
        $itemValue = $item[$valueKey];
        $itemText = $item[$textKey];
        $isSelected = $itemValue == $selected ? 'selected' : '';
        
        $options .= "<option value=\"$itemValue\" $isSelected>$itemText</option>";
    }
    
    return $options;
}

/**
 * Date formatting
 */
function formatDate($date, $format = 'd/m/Y H:i') {
    if (is_string($date)) {
        $date = strtotime($date);
    }
    return date($format, $date);
}

function getTimeAgo($date) {
    $timestamp = strtotime($date);
    $diff = time() - $timestamp;
    
    if ($diff < 60) {
        return 'Vừa xong';
    } elseif ($diff < 3600) {
        $mins = intval($diff / 60);
        return $mins . ' phút trước';
    } elseif ($diff < 86400) {
        $hours = intval($diff / 3600);
        return $hours . ' giờ trước';
    } else {
        $days = intval($diff / 86400);
        return $days . ' ngày trước';
    }
}

/**
 * String utilities
 */
function startsWith($haystack, $needle) {
    return strpos($haystack, $needle) === 0;
}

function endsWith($haystack, $needle) {
    return strrpos($haystack, $needle) === strlen($haystack) - strlen($needle);
}

function contains($haystack, $needle) {
    return strpos($haystack, $needle) !== false;
}

/**
 * Math utilities
 */
function percentage($value, $total) {
    return $total > 0 ? ($value / $total) * 100 : 0;
}

function discount($originalPrice, $discountPercent) {
    return $originalPrice - ($originalPrice * $discountPercent / 100);
}

/**
 * Validation
 */
function isEmpty($value) {
    return empty($value);
}

function isEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

function isNumeric($value) {
    return is_numeric($value);
}

function isInteger($value) {
    return is_int($value) || (is_string($value) && ctype_digit($value));
}

function minLength($value, $length) {
    return strlen($value) >= $length;
}

function maxLength($value, $length) {
    return strlen($value) <= $length;
}

/**
 * URL utilities
 */
function addQueryParam($url, $key, $value) {
    $separator = strpos($url, '?') !== false ? '&' : '?';
    return $url . $separator . $key . '=' . urlencode($value);
}

function removeQueryParam($url, $key) {
    return preg_replace('~[?&]' . $key . '=[^&]*~', '', $url);
}

function buildQuery($params) {
    return http_build_query($params);
}

/**
 * Array to CSV
 */
function arrayToCsv($array, $filename = 'export.csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    
    $output = fopen('php://output', 'w');
    
    foreach ($array as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
}

/**
 * JSON response
 */
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Success response
 */
function successResponse($message = 'Success', $data = []) {
    return jsonResponse([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}

/**
 * Error response
 */
function errorResponse($message = 'Error', $statusCode = 400) {
    return jsonResponse([
        'success' => false,
        'message' => $message
    ], $statusCode);
}

/**
 * Debug function
 */
function debug($var, $die = false) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    
    if ($die) {
        die;
    }
}

/**
 * Log to file
 */
function logToFile($message, $filename = 'app.log') {
    $timestamp = date('Y-m-d H:i:s');
    $log = "[$timestamp] $message\n";
    file_put_contents(BASE_PATH . '/logs/' . $filename, $log, FILE_APPEND);
}
