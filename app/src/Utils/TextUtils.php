<?php

namespace Utils;

/**
 * 
 * TextUtils
 * 
 * Хелпер для форматирования текстов
 * 
 */
class TextUtils {
  
  /**
   * 
   * formatTime
   * 
   * Форматирует время (ЧЧ:ММ) из секунд
   *
   * @param  int $time
   * @return void
   */
  public static function formatTime(int $time) {
    $hours = floor($time / (60 * 60));
    $minutes = floor(($time - ($hours * 60 * 60)) / 60);
    return 
      ($hours <= 9 ? "0" . $hours : $hours)
      . ":" . ($minutes <= 9 ? "0" . $minutes : $minutes);
  }
  
}