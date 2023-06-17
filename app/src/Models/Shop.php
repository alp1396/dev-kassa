<?php

namespace Models;

/**
 * 
 * Модель магазина
 * 
 **/
class Shop
{

  public array $kasses;

  public function Update(int $time)
  {
    foreach ($this->kasses as $kassa) {
      $kassa->Update($time);
    }
  }

  function __construct() {
    for ($i = 0; $i < MAX_KASS; $i++) {
      $this->kasses []= new Kassa();
    }
  }
    
  /**
   * 
   * AppendClients
   * 
   * Добавление клиентов в магазин
   *
   * @param  Client[] $clients
   * @return void
   */
  public function AppendClients(array $clients) {

    /**
     * 
     * Для каждого клиента из пришедших в магазин ...
     * 
     */
    foreach($clients as $client) {
      /**
       * 
       * Поиск свободной кассы, либо выбор кассы которая самая не заполненная
       * 
       */
      $kassaMin = array_reduce($this->kasses, function($curr, $value) {
        
        // Первый элемент не с чем сравнить - по умолчанию берем его
        if ($curr == null) return $value;
        
        // Если касса свободна - берем ее
        if (!$curr->isFull()) return $curr;

        // Иначе сравниваем на предмет количества клиентов в очереди
        if ($curr->queue->Count() <= $value->queue->Count()) {
          return $curr;
        } else {
          return $value;
        }

      });

      // Выбранной кассе отдаем клиента в очередь
      $kassaMin->Append($client);

    }
  }

  public function GetClientsProcessedTotal() {
    $result = 0;
    foreach($this->kasses as $kassa) {
      $result += $kassa->GetClientsProcessed();
    }
    return $result;
  }
}
