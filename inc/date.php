<?php 

    $substract_year = $_COOKIE["years"];

    $past_year_start = new DateTime();
    $past_year_start->getTimestamp();
    $past_year_start->sub(new DateInterval('P'.$substract_year.'Y'));
    $past_year_start->setTime(00, 00);
    $start = $past_year_start->getTimestamp();

    $past_year_stop = new DateTime();
    $past_year_stop->getTimestamp();
    $past_year_stop->sub(new DateInterval('P'.$substract_year.'Y'));
    $past_year_stop->setTime(23, 59);
    $stop = $past_year_stop->getTimestamp();