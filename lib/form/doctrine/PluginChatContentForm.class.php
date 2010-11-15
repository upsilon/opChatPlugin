<?php

/**
 * PluginChatContent form.
 *
 * @package    opChatPlugin
 * @subpackage form
 * @author     Youichi Kimura <kim.upsilon@gmail.com>
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
