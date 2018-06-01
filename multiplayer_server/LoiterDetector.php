<?php

namespace pr2\multi;

class LoiterDetector
{

    private static $loitering_ips = array();
    private static $level_lists = array();


    public static function addLevelList($level_list)
    {
        array_push(self::$level_lists, $level_list);
    }


    public static function check()
    {
        foreach (self::$loitering_ips as $ip => $time) {
            self::$loitering_ips[$ip] = $time - 1;
            if ($time <= 0) {
                unset(self::$loitering_ips[$ip]);
            }
        }

        foreach (self::$level_lists as $level_list) {
            foreach ($level_list->course_array as $course) {
                if (count($course->slot_array) >= 4) {
                    foreach ($course->slot_array as $player) {
                        if (!$player->confirmed) {
                            $ip = $player->ip;
                            if (!isset(self::$loitering_ips[$ip])) {
                                self::$loitering_ips[$ip] = 0;
                            }
                            self::$loitering_ips[$ip] += 2;
                            if (self::$loitering_ips[$ip] > 30) {
                                self::stopLoiterer($ip);
                            }
                        }
                    }
                }
            }
        }
    }


    public static function stopLoiterer($ip)
    {
        output("LoiterDetector::stopLoiterer $ip");
        foreach (self::$level_lists as $level_list) {
            foreach ($level_list->player_array as $player) {
                if ($player->ip === $ip) {
                    if (isset($player->course_box)) {
                        $player->course_box->clearSlot($player);
                    }
                }
            }
        }
    }
}
