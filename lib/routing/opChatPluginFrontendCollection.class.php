<?php

class opChatPluginFrontendRouteCollection extends sfRouteCollection
{
  public function __construct(array $options)
  {
    parent::__construct($options);

    $this->routes = array(
      'chatroom_list' => new sfRequestRoute(
        '/chat',
        array('module' => 'chat', 'action' => 'index'),
        array('sf_method' => array('get'))
      ),
      'chatroom_new' => new sfRequestRoute(
        '/chat/new',
        array('module' => 'chat', 'action' => 'new'),
        array('sf_method' => array('get'))
      ),
      'chatroom_create' => new sfRequestRoute(
        '/chat/create',
        array('module' => 'chat', 'action' => 'create'),
        array('sf_method' => array('post'))
      ),
      'chatroom_edit' => new sfDoctrineRoute(
        '/chat/edit/:id',
        array('module' => 'chat', 'action' => 'edit'),
        array('id' => '\d+', 'sf_method' => array('get')),
        array('model' => 'ChatRoom', 'type' => 'object')
      ),
      'chatroom_update' => new sfDoctrineRoute(
        '/chat/update/:id',
        array('module' => 'chat', 'action' => 'update'),
        array('id' => '\d+', 'sf_method' => array('post')),
        array('model' => 'ChatRoom', 'type' => 'object')
      ),
      'chatroom_show' => new sfDoctrineRoute(
        '/chat/show/:id',
        array('module' => 'chat', 'action' => 'show'),
        array('id' => '\d+', 'sf_method' => array('get')),
        array('model' => 'ChatRoom', 'type' => 'object')
      ),
      'chatroom_post' => new sfDoctrineRoute(
        '/chat/post/:id',
        array('module' => 'chat', 'action' => 'post'),
        array('id' => '\d+', 'sf_method' => array('post')),
        array('model' => 'ChatRoom', 'type' => 'object')
      ),
      'chatroom_heartbeat' => new sfDoctrineRoute(
        '/chat/heartbeat/:id',
        array('module' => 'chat', 'action' => 'heartbeat'),
        array('id' => '\d+', 'sf_method' => array('get')),
        array('model' => 'ChatRoom', 'type' => 'object')
      ),
      'chatroom_close_confirm' => new sfDoctrineRoute(
        '/chat/close/:id',
        array('module' => 'chat', 'action' => 'closeConfirm'),
        array('id' => '\d+', 'sf_method' => array('get')),
        array('model' => 'ChatRoom', 'type' => 'object')
      ),
      'chatroom_close' => new sfDoctrineRoute(
        '/chat/close/:id',
        array('module' => 'chat', 'action' => 'close'),
        array('id' => '\d+', 'sf_method' => array('post')),
        array('model' => 'ChatRoom', 'type' => 'object')
      ),
      // no default
      'chat_nodefaults' => new sfRoute(
        '/chat/*',
        array('module' => 'default', 'action' => 'error')
      ),
    );
  } 
}
