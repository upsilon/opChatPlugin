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

    if (!$room->isActive($member->id))
    {
      $room->login($member);
    }

    $this->redirect('@chatroom_show?id='.$room->id);
  }

  public function executeLogout(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();
    $member = $this->getUser()->getMember();

    $this->forward404Unless($room->isOpened());

    if ($room->isActive($member->id))
    {
      $room->logout($member);
    }

    $this->redirect('@chatroom_list');
  }

  public function executeShow(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();
    $member_id = $this->getUser()->getMemberId();

    if (!$request->isXmlHttpRequest())
    {
      $this->redirectIf($room->is_closed, '@chatroom_log?id='.$room->id);
    }

    $this->forward404Unless($room->isWritable() && $room->isActive($member_id));

    $last = $request->getParameter('last', 0);

    $this->memberlist = Doctrine::getTable('ChatRoomMember')->getMembers($room);

    if ($request->isXmlHttpRequest())
    {
      switch ($request->getParameter('view'))
      {
        case 'chat':
          $table = Doctrine::getTable('ChatContent');
          $chat = $table->getList($room, $last);
          $json = $table->getListJson($this->getController(), $chat);
          return $this->renderJson($json);
        case 'member':
          return $this->renderPartial('chat/memberlist');
        default:
          return sfView::NONE;
      }
    }

    $this->chatlist = Doctrine::getTable('ChatContent')->getList($room, $last);

    $chat = new ChatContent();
    $chat->ChatRoom = $room;
    $chat->member_id = $member_id;
    $this->form = new ChatContentForm($chat);

    $this->room = $room;
  }

  public function executePost(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();
    $member_id = $this->getUser()->getMemberId();

    $this->forward404Unless($room->isWritable() && $room->isActive($member_id));

    $chat = new ChatContent();
    $chat->ChatRoom = $room;
    $chat->member_id = $member_id;

    $this->form = new ChatContentForm($chat);
    $this->form->bindAndSave($request->getParameter('chat_content'));

    if ($request->isXmlHttpRequest())
    {
      $request->setParameter('view', 'chat');
      $this->forward('chat', 'show');
    }
    else
    {
      $this->forward('chat', 'heartbeat');
    }
  }

  public function executeHeartbeat(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();
    $member_id = $this->getUser()->getMemberId();

    $this->forward404Unless($room->isWritable() && $room->isActive($member_id));

    Doctrine::getTable('ChatRoomMember')->update($member_id, $room);

    if ($request->isXmlHttpRequest())
    {
      return sfView::HEADER_ONLY;
    }
    else
    {
      $this->redirect('@chatroom_show?id='.$room->id);
    }
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new ChatRoomForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $room = new ChatRoom();
    $room->member_id = $this->getUser()->getMemberId();
    $this->form = new ChatRoomForm($room);
    if ($this->form->bindAndSave($request->getParameter('chat_room')))
    {
      if ($cache = $this->getContext()->getViewCacheManager())
      {
        $cache->remove('chat/index');
      }

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
    $member_id = $this->getUser()->getMemberId();

    // もし、作成者でない場合は404画面に飛ばす
    $this->forward404Unless($room->isEditable($member_id));

    $this->form = new ChatRoomForm($room);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();
    $member_id = $this->getUser()->getMemberId();

    $this->forward404Unless($room->isEditable($member_id));
    
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

    if ($cache = $this->getContext()->getViewCacheManager())
    {
      $cache->remove('chat/index');
    }

    $this->getUser()->setFlash('notice', $room->getTitle().' を終了しました');
    $this->redirect('@chatroom_list');
  }

  public function executeLog(sfWebRequest $request)
  {
    $room = $this->getRoute()->getObject();

    $this->forward404Unless($room->isOpened());

    $this->room = $room;
    $this->pager = Doctrine::getTable('ChatContent')->getListPager($room, $request->getParameter('page'));
  }

  protected function renderJson($json)
  {
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');

    return $this->renderText($json);
  }
}
