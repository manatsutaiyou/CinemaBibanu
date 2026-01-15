<?php
require_once '../config/config.php';
require_once '../core/Database.php';

$db = new Database();

$hours = ['11:00:00','12:00:00','13:00:00','14:00:00','15:00:00','16:00:00','17:00:00'];

$movies = $db->query("SELECT id FROM movies");

$start = new DateTime();
$end   = (new DateTime())->modify('+365 days');

while ($start <= $end) {

    $date = $start->format('Y-m-d');

    while ($movie = $movies->fetch_assoc()) {
        foreach ($hours as $hour) {

            $db->query("
                INSERT IGNORE INTO screenings (movie_id, screening_date, screening_time)
                VALUES ({$movie['id']}, '$date', '$hour')
            ");
        }
    }

    $start->modify('+1 day');
    $movies->data_seek(0);
}

echo "Programări generate!";
