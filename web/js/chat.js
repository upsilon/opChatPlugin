var lastID = 0;

function checkLastID()
{
  var num = $('chatview').getElementsByClassName('number');
  if (num.length == 0) return;
  lastID = parseInt(num[num.length - 1].innerHTML);
}

function update() {
  new Ajax.Updater('chatview_dl', url['show'], {
    method: 'get',
    parameters: { last: lastID },
    insertion: Insertion.Bottom,
    onComplete: checkLastID
  });
}

function post(param) {
  param['last'] = lastID;
  new Ajax.Updater('chatview_dl', url['post'], {
    method: 'post',
    parameters: param,
    insertion: Insertion.Bottom,
    onComplete: checkLastID
  });
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
};
