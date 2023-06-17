<?php

namespace Models;

use Classes\Queue;

/**
 * 
 * Модель кассы магазина 
 * 
 */
class Kassa
{

  private int $chekoutTimer = 0;
  private int $paymentTimer = 0;
  private int $timeToClose = 0;
  private int $clientsProcessed = 0;

  public Queue $queue;

  function __construct()
  {
    $this->queue = new Queue();
  }

  /**
   * 
   * Update
   * 
   * Обновление кассы в соответствии с прошедшим временем. 
   * Обновление таймеров, таймаута на закрытие и т.д.
   *
   * @param int $time
   * @return void
   */
  public function Update(int $time): void
  {
    if ($this->chekoutTimer > 0) {
      $this->chekoutTimer -= $time;
      return;
    }

    if ($this->paymentTimer > 0) {
      $this->paymentTimer -= $time;
      return;
    }

    if ($this->timeToClose > 0) {
      $this->timeToClose -= $time;
    }

    $this->TakeClientFromQueue();
  }
  
  /**
   * 
   * TakeClientFromQueue
   * 
   * Забираем клиента из очереди, обнуляем таймеры на обработку товаров, 
   * олпату, закрытие кассы при простое.
   *
   * @return void
   */
  protected function TakeClientFromQueue(): void
  {
    $client = $this->queue->pop();
    if ($client == null) return;
    $this->chekoutTimer = CLIENT_CHECKOUT_TIME;
    $this->paymentTimer = CLIENT_PAYMENT_TIME;
    $this->timeToClose = KASS_CLOSE_TIMEOUT;
    $this->clientsProcessed += 1;
  }
  
  /**
   * 
   * isOpen
   * 
   * Проверка открыта ли касса
   *
   * @return bool
   */
  public function isOpen(): bool
  {
    return $this->timeToClose > 0;
  }

    
  /**
   * 
   * isFree
   * 
   * Проверка свободна ли касса
   *
   * @return bool
   */
  public function isFree(): bool
  {
    return $this->timeToClose > 0 && $this->chekoutTimer <= 0 && $this->paymentTimer <= 0;
  }
  
  /**
   * 
   * isFull
   * 
   * Проверка заполнена ли очередь в кассе
   *
   * @return bool
   */
  public function isFull(): bool
  {
    return $this->queue->Count() >= MAX_KASS_QUEUE;
  }

  public function GetClientsProcessed(): int
  {
    return $this->clientsProcessed;
  }
  
  /**
   * 
   * Append
   * 
   * Добавление клиента в очередь кассы
   *
   * @param  mixed $client
   * @return void
   */
  public function Append(Client $client): void
  {
    $this->queue->Push($client);
  }
}
