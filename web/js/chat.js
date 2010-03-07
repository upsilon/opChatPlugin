var updateInterval = 5000;
var heartbeatInterval = 30000;

var lastID = 0;

function checkLastID() {
  var num = $('chatview').getElementsByClassName('number');
  if (num.length == 0) return;
  lastID = parseInt(num[num.length - 1].innerHTML);
}

function scroll(obj) {
  // 一番下までスクロール
  obj.scrollTop = 999999;
}

function chatviewUpdated() {
  checkLastID();
  scroll($('chatview'));
}

function update() {
  new Ajax.Updater({success: 'memberlist'}, url['show'], {
    method: 'get',
    parameters: { view: 'member' }
  });
  new Ajax.Updater({success: 'chatview'}, url['show'], {
    method: 'get',
    parameters: { view: 'chat', last: lastID },
    insertion: Insertion.Bottom,
    onComplete: chatviewUpdated
  });
}

function post(param) {
  param['last'] = lastID;
  new Ajax.Updater({success: 'chatview'}, url['post'], {
    method: 'post',
    parameters: param,
    insertion: Insertion.Bottom,
    onComplete: chatviewUpdated
  });
}

function heartbeat() {
  new Ajax.Request(url['heartbeat'], {
    method: 'post',
  });
}

function updateTimer() {
  update();
  setTimeout(updateTimer, updateInterval);
}

function heartbeatTimer() {
  heartbeat();
  setTimeout(heartbeatTimer, heartbeatInterval);
}

window.onload = function () {
  chatviewUpdated();

  $('chat_content').onsubmit = function() {
    if ($F('chat_content_body') != '') {
      post(Form.serialize('chat_content', true));
    }
    else {
      update();
    }

    return false;
  };

  setTimeout(updateTimer, updateInterval);
  setTimeout(heartbeatTimer, heartbeatInterval);
};
