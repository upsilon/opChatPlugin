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
 * PluginChatContent form.
 *
 * @package     opChatPlugin
 * @subpackage  form
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 */

abstract class PluginChatContentForm extends BaseChatContentForm
{
  public function setup()
  {
    parent::setup();

    $this->setWidget('body', new opWidgetFormRichTextareaOpenPNE());
    $this->setValidator('body', new opValidatorString(array(
      'max_length' => 256,
      'rtrim' => true,
    )));

    $this->useFields(array('body'));
  }
}
