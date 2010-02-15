<?php

/**
 * PluginChatRoom form.
 *
 * @package    opChatPlugin
 * @subpackage form
 * @author     Youichi Kimura <kim.upsilon@gmail.com>
 */
abstract class PluginChatRoomForm extends BaseChatRoomForm
{
  public function setup()
  {
    parent::setup();

    $this->setWidget('title', new sfWidgetFormInputText());
    $this->setValidator('title',
      new opValidatorString(array('max_length' => 140, 'trim' => true)));

    $this->setWidget('open_date', new opWidgetFormDateTime(array(
        'culture' => sfContext::getInstance()->getUser()->getCulture(),
        'date' => array('can_be_empty' => true, 'month_format' => 'number'),
        'time' => array('can_be_empty' => true),
      )));
    $this->setValidator('open_date', new opValidatorDate(array(
        'required' => false,
        'with_time' => true,
        'min' => 'now',
      ), array('min' => '過去の日時を指定することはできません')));
    $this->widgetSchema->setHelp('open_date',
      'すぐに開始する場合は空欄のままにしてください');
    $this->widgetSchema->setLabel('open_date', '開始日時');

    $this->useFields(array('title', 'open_date'));
  }
}
