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
 * opChatPluginConfiguration
 *
 * @package     opChatPlugin
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 */

class opChatPluginConfiguration extends sfPluginConfiguration
{
  static protected $defaultIntervalConfigs = array(
    'updateInterval' => 5,
    'updateMemberListInterval' => 60,
    'heartbeatInterval' => 30,
  );
  static protected $configPrefix = 'opchat_';

  public function initialize()
  {
  }

  static public function getIntervalConfigs()
  {
    $configTable = Doctrine::getTable('SnsConfig');
    $configArray = array();
    foreach (self::$defaultIntervalConfigs as $key => $default)
    {
      $configName = self::$configPrefix.sfInflector::underscore($key);
      $configArray[$key] = $configTable->get($configName, $default);
    }

    return $configArray;
  }


  static public function setIntervalConfigs($values)
  {
    $configTable = Doctrine::getTable('SnsConfig');
    foreach (self::$defaultIntervalConfigs as $key => $default)
    {
      if (!isset($values[$key]))
      {
        continue;
      }

      $configName = self::$configPrefix.sfInflector::underscore($key);
      $value = $values[$key];

      if ($value === $default)
      {
        $record = $configTable->retrieveByName($configName);
        if ($record)
        {
          $record->delete();
        }
      }
      else
      {
        $configTable->set($configName, $value);
      }
    }
  }
}
