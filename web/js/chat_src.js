(function (){
  // interval
  var updateInterval = 5;
  var updateMemberListInterval = 60;
  var heartbeatInterval = 30;

  var url = {};
  var updateTimer = null;
  var updateMemberListTimer = null;
  var heartbeatTimer = null;

  var lastID = 0;

  var html = '<dt>'
           + '<span class="number">#{number}</span>: '
           + '<a href="#{member_url}">#{member_name}</a> '
           + '#{created_at}'
           + '</dt>'
           + '<dd class="#{command}">#{body}</dd>';
  var contentTemplate = new Template(html);

  function checkLastID() {
    var num = $('chatview').getElementsByClassName('number');
    if (num.length == 0) return;
    lastID = parseInt(num[num.length - 1].innerHTML);
  }

  function scroll(obj) {
    // 一番下までスクロール
    obj.scrollTop = 999999;
  }

  function chatviewUpdated(response) {
    var json = response.responseJSON;

    if (!json || json.length == 0) {
      return;
    }

    var html = '';
    json.each(function (content) {
      // 受信済みのIDを二度受信してしまった場合は破棄する
      if (content.number <= lastID) {
        return;
      }

      lastID = content.number;
      html += contentTemplate.evaluate(content);
    });
    $('chatview').innerHTML += html;

    checkLastID();
    scroll($('chatview'));
  }

  function update() {
    new Ajax.Request(url['show'], {
      method: 'get',
      parameters: { view: 'chat', last: lastID },
      insertion: Insertion.Bottom,
      onComplete: function (response) {
        chatviewUpdated(response);
        startUpdateTimer();
      },
    });
  }

  function startUpdateTimer() {
    updateTimer = setTimeout(update, updateInterval * 1000);
  }

  function updateMemberList()
  {
    new Ajax.Updater({success: 'memberlist'}, url['show'], {
      method: 'get',
      parameters: { view: 'member' },
      onComplete: function () {
        startUpdateMemberListTimer();
      },
    });
  }

  function startUpdateMemberListTimer() {
    updateMemberListTimer = setTimeout(updateMemberList, updateMemberListInterval * 1000);
  }

  function post(param) {
    param['last'] = lastID;
    new Ajax.Request(url['post'], {
      method: 'post',
      parameters: param,
      insertion: Insertion.Bottom,
      onComplete: function (response) {
        chatviewUpdated(response);
        $('chat_content_body').setValue('');
      },
    });
  }

  function heartbeat() {
    new Ajax.Request(url['heartbeat'], {
      method: 'post',
      onComplete: function () {
        startHeartbeatTimer();
      },
    });
  }

  function startHeartbeatTimer() {
    heartbeatTimer = setTimeout(heartbeat, heartbeatInterval * 1000);
  }

  Event.observe(window, 'load', function(evt) {
    url = url_for_op_chat;

    checkLastID();
    scroll($('chatview'));

    Event.observe('chat_content', 'submit', function(evt) {
      if ($F('chat_content_body') != '') {
        post(Form.serialize('chat_content', true));
      }
      else {
        update();
      }
      Event.stop(evt);
    });

    startUpdateTimer();
    startUpdateMemberListTimer();
    startHeartbeatTimer();
  });
}());
