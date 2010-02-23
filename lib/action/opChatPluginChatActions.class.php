<?php

class opChatPluginChatActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->pager = Doctrine::getTable('ChatRoom')->getListPager($request->getParameter('page'));
  }

  public function executeLogin(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();
    $member = $this->getUser()->getMember();

    $this->forward404Unless($room->isOpened());
    $this->redirectUnless(!$room->is_closed, '@chatroom_log?id='.$room->id);

    if (!$room->isActive($member))
    {
      Doctrine::getTable('ChatRoomMember')->login($member, $room);
      Doctrine::getTable('ChatContent')->login($member, $room);
    }

    $this->redirect('@chatroom_show?id='.$room->id);
  }

  public function executeLogout(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();
    $member = $this->getUser()->getMember();

    $this->forward404Unless($room->isOpened());

    if ($room->isActive($member))
    {
      Doctrine::getTable('ChatRoomMember')->logout($member, $room);
      Doctrine::getTable('ChatContent')->logout($member, $room);
    }

    $this->redirect('@chatroom_list');
  }

  public function executeShow(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();
    $member = $this->getUser()->getMember();

    if (!$request->isXmlHttpRequest())
    {
      $this->redirectIf($room->is_closed, '@chatroom_log?id='.$room->id);
    }

    $this->forward404Unless($room->isWritable() && $room->isActive($member));

    $last = $request->getParameter('last', 0);
    $this->chatlist = Doctrine::getTable('ChatContent')->getList($room, $last);

    $this->memberlist = Doctrine::getTable('ChatRoomMember')->getMembers($room);

    if ($request->isXmlHttpRequest())
    {
      switch ($request->getParameter('view'))
      {
        case 'chat':
          return $this->renderPartial('chat/chatview');
        case 'member':
          return $this->renderPartial('chat/memberlist');
        default:
          return sfView::NONE;
      }
    }

    $chat = new ChatContent();
    $chat->setChatRoom($room);
    $chat->setMember($member);
    $this->form = new ChatContentForm($chat);

    $this->room = $room;
  }

  public function executePost(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();
    $member = $this->getUser()->getMember();

    $this->forward404Unless($room->isWritable() && $room->isActive($member));

    $chat = new ChatContent();
    $chat->setChatRoom($room);
    $chat->setMember($member);

    $this->form = new ChatContentForm($chat);
    $this->form->bindAndSave($request->getParameter('chat_content'));

    if ($request->isXmlHttpRequest())
    {
      $request->setParameter('view', 'chat');
      $this->forward('chat', 'show');
    }

    $this->redirect('@chatroom_show?id='.$room->getId());
  }

  public function executeHeartbeat(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();
    $member = $this->getUser()->getMember();

    $this->forward404Unless($room->isWritable() && $room->isActive($member));

    $member = $this->getUser()->getMember();
    Doctrine::getTable('ChatRoomMember')->update($member, $room);

    return sfView::HEADER_ONLY;
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ChatRoomForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $room = new ChatRoom();
    $room->setMember($this->getUser()->getMember());
    $this->form = new ChatRoomForm($room);
    if ($this->form->bindAndSave($request->getParameter('chat_room')))
    {
      if (is_null($room->open_date))
      {
        $this->redirect('@chatroom_login?id='.$room->id);
      }
      else
      {
        $this->redirect('@chatroom_list');
      }
    }
    // newのテンプレートを使う
    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    // モデルオブジェクトをルートから取得
    $room = $this->getRoute()->getObject();
    // もし、作成者でない場合は404画面に飛ばす
    $this->forward404Unless($room->isEditable($this->getUser()));

    $this->forward404Unless(!$room->is_closed);

    $this->form = new ChatRoomForm($room);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();
    $this->forward404Unless($room->isEditable($this->getUser()));
    
    $this->form = new ChatRoomForm($room);
    if ($this->form->bindAndSave($request->getParameter('chat_room')))
    {
      $this->getUser()->setFlash('notice', '編集しました');
      $this->redirect('@chatroom_list');
    }
    $this->setTemplate('edit');
  }

  public function executeCloseConfirm(sfWebRequest $request)
  {
    $this->room = $this->getRoute()->getObject();
  }

  public function executeClose(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
    $room = $this->getRoute()->getObject();
    $room->setIsClosed(true);
    $room->save();

    $this->getUser()->setFlash('notice', $room->getTitle().' を終了しました');
    $this->redirect('@chatroom_list');
  }

  public function executeLog(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();

    $this->forward404Unless($room->is_closed);

    $this->room = $room;
    $this->pager = Doctrine::getTable('ChatContent')->getListPager($room, $request->getParameter('page'));
  }

}
