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
 * opChatPlugin actions.
 *
 * @package     opChatPlugin
 * @author      Kimura Youichi <kim.upsilon@gmail.com>
 */
class opChatPluginActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new opChatIntervalForm();
  }

  public function executeUpdateInterval(sfWebRequest $request)
  {
    $this->form = new opChatIntervalForm();
    $this->form->bind($request->getParameter('interval'));
    if ($this->form->isValid())
    {
      $this->form->save();
      $this->getUser()->setFlash('notice', 'Saved.');
    }

    $this->setTemplate('index');
  }
}
