var updateInterval = 5000;
var heartbeatInterval = 30000;

var lastID = 0;

function checkLastID()
{
  var num = $('chatview').getElementsByClassName('number');
  if (num.length == 0) return;
  lastID = parseInt(num[num.length - 1].innerHTML);
}

function update() {
  new Ajax.Updater('memberlist', url['show'], {
    method: 'get',
    asynchronous: false,
    parameters: { view: 'member' }
  });
  new Ajax.Updater('chatview', url['show'], {
    method: 'get',
    asynchronous: false,
    parameters: { view: 'chat', last: lastID },
    insertion: Insertion.Bottom,
    onComplete: checkLastID
  });
}

function post(param) {
  param['last'] = lastID;
  new Ajax.Updater('chatview', url['post'], {
    method: 'post',
    parameters: param,
    insertion: Insertion.Bottom,
    onComplete: checkLastID
  });
}

function heartbeat() {
  new Ajax.Request(url['heartbeat'], {
    method: 'post',
    asynchronous: false
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
  checkLastID();

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
