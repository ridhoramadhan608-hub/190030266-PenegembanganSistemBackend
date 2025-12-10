<?php
/**
 * Small sanitization helpers used server-side.
 */

function sanitize_text($str)
{
    return trim(htmlspecialchars((string)$str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
}

function sanitize_date($date)
{
    // Accepts YYYY-MM-DD or empty
    $date = trim($date);
    if ($date === '') return null;
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date ? $date : null;
}
