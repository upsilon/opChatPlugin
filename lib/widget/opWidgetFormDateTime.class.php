<?php

class opWidgetFormDateTime extends sfWidgetFormI18nDateTime
{
  /**
   * @see sfWidgetFormI18nDateTime
   */
  public function getDateWidget($attributes = array())
  {
    return new opWidgetFormDate(array_merge(array('culture' => $this->getOption('culture')), $this->getOptionsFor('date')), $this->getAttributesFor('date', $attributes));
  }
}

