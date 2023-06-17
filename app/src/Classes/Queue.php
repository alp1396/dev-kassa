<?php

namespace Classes;

/**
 * Класс очереди FIFO
 */
class Queue
{
  protected $items = [];

  public function Push($item)
  {
    $this->items[] = $item;
  }

  public function Pop()
  {
    if ($this->Count() <= 0) return null;
    $result = $this->items[0];
    array_splice($this->items, 0, 1);
    return $result;
  }

  public function Count()
  {
    return count($this->items);
  }
}
