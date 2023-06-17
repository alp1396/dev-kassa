<?php

namespace Services;

use Models\Client;

/**
 * 
 * ClientGenerator
 * 
 * Создает псевдо-пиковый поток покупателей в зависимости от времени прошедшего
 * с начала открытия магазина, пик формируется за счет синуса от дельты времени
 * 
 */
class ClientGenerator {

  protected static function GetPeak($v) {
    return sin($v);
  }

  public static function GetClients($time, $timeTotal, $clientsRate) {
    
    /// Получаем значение синуса из диапазона времени [0, 1]
    $value = self::GetPeak(($time / $timeTotal) * pi());
    
    /// Увеличиваем пиковое значение кратно максимальному потоку клиентов
    $value *= $clientsRate;

    $result = [];
    /// Генерируем новых клиентов в соответствии с количеством
    for($i = 0; $i < round($value); $i++) {
      $result []= new Client();
    }
    return $result;

  }
}