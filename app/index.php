<?php

require __DIR__ . '/vendor/autoload.php';

define('MAX_KASS', 5); // Макс. количество касс
define('CLIENT_CHECKOUT_TIME', 60); // Время на клиента для пробивания товара
define('CLIENT_PAYMENT_TIME', 20); // Время на клиента для оплаты товара
define('CLIENTS_RATE_PER_MINUTE', 4); // Количество посетителей в минуту 
define('KASS_CLOSE_TIMEOUT', 60); // Таймаут для закрытия пустой кассы
define('MAX_KASS_QUEUE', 5); // Макс очередь в кассу

define('TIME_SPEED', 1); // Модификатор скорости времени
define('SHOP_OPEN_TIME', 7 * 60 * 60); // Время начала работы магазина
define('SHOP_CLOSE_TIME', 23 * 60 * 60); // Время окончания работы магазина

use Models\Shop;
use Services\ClientGenerator;
use Utils\TextUtils;

$shop = new Shop();

$time = 0;
$timeEnd = SHOP_CLOSE_TIME - SHOP_OPEN_TIME;

/**
 * Основной цикл симуляции
 */
while ($time <= $timeEnd) {

  /**
   *  
   * Каждый час выводим состояние касс в магазине
   * 
   */
  if ($time % (60 * 60) <= 0) {
    $log = TextUtils::formatTime(SHOP_OPEN_TIME + $time) . ' >>> ';
    foreach ($shop->kasses as $id => $kassa) {
      if ($kassa->isOpen()) {
        $log .= " | Касса №" . ($id + 1) . ": " . $kassa->queue->Count() . " ";
      }
    }
    $log .= "\r\n";
    echo $log;
  }

  /**
   * 
   * Каждую минуту вбрасываем новых покупателей
   * 
   */
  if ($time % (60) <= 0) {
    $clients = ClientGenerator::GetClients(
      $time,
      $timeEnd,
      CLIENTS_RATE_PER_MINUTE
    );
    $shop->AppendClients($clients);
  }

  /**
   * 
   * Обновляем основной цикл магазина
   * 
   */
  $shop->Update(TIME_SPEED);

  $time += TIME_SPEED;
}

echo "Всего обработано клиентов: " . $shop->GetClientsProcessedTotal() . "\r\n";
