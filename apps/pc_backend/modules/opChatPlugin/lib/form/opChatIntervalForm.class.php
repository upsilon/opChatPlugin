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
 * opChatIntervalForm
 *
 * @package     opChatPlugin
 * @subpackage  form
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 */
class opChatIntervalForm extends BaseForm
{
  private static $widgetLabel = array(
  );

  public function configure()
  {
    $intervalConfigs = opChatPluginConfiguration::getIntervalConfigs();
    foreach ($intervalConfigs as $key => $value)
    {
      $this->setWidget($key, new sfWidgetFormInputText());
      $this->setValidator($key, new sfValidatorInteger(array('min' => 1)));
      $this->setDefault($key, $value);

      if (isset(self::$widgetLabel[$key]))
      {
        $this->widgetSchema->setLabel($key, self::$widgetLabel[$key]);
      }
    }

    $this->widgetSchema->setNameFormat('interval[%s]');
  }

  public function save()
  {
    opChatPluginConfiguration::setIntervalConfigs($this->getValues());
  }
}
