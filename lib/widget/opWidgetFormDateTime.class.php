<?php

/**
 * opChatPlugin
 *
 * This source file is subject to the Apache License version 2.0
 * that is bundled with this package in the file LICENSE.
 *
 * @license     Apache License 2.0
 */

/**
 * opWidgetFormDateTime
 *
 * @package     opChatPlugin
 * @subpackage  widget
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 */

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

