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

    $this->setWidget('command', new sfWidgetFormInputText());
    $this->setValidator('command', new opValidatorString(
      array('required' => false, 'max_length' => 64, 'trim' => true)));
    $this->widgetSchema->setLabel('command', 'コマンド');

    $this->setWidget('body', new sfWidgetFormInputText());
    $this->setValidator('body', new opValidatorString(
      array('max_length' => 256)));

    $this->useFields(array('command', 'body'));
  }
}
