<?php

class Analytics
{
    public static function track($page)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $db = new Database();
        $sid = session_id();

        $db->query("
            INSERT INTO site_visits (session_id, page, visit_date)
            VALUES ('$sid', '$page', NOW())
        ");
    }
}
