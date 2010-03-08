(function(){var updateInterval=5;var updateMemberListInterval=60;var heartbeatInterval=30;var url={};var updateTimer=null;var updateMemberListTimer=null;var heartbeatTimer=null;var lastID=0;function checkLastID(){var num=$('chatview').getElementsByClassName('number');if(num.length==0)return;lastID=parseInt(num[num.length-1].innerHTML);}
function scroll(obj){obj.scrollTop=999999;}
function chatviewUpdated(){checkLastID();scroll($('chatview'));}
function update(){new Ajax.Updater({success:'chatview'},url['show'],{method:'get',parameters:{view:'chat',last:lastID},insertion:Insertion.Bottom,onComplete:function(){chatviewUpdated();startUpdateTimer();},});}
function startUpdateTimer(){updateTimer=setTimeout(update,updateInterval*1000);}
function updateMemberList()
{new Ajax.Updater({success:'memberlist'},url['show'],{method:'get',parameters:{view:'member'},onComplete:function(){startUpdateMemberListTimer();},});}
function startUpdateMemberListTimer(){updateMemberListTimer=setTimeout(updateMemberList,updateMemberListInterval*1000);}
function post(param){param['last']=lastID;new Ajax.Updater({success:'chatview'},url['post'],{method:'post',parameters:param,insertion:Insertion.Bottom,onComplete:function(){chatviewUpdated();$('chat_content_body').setValue('');},});}
function heartbeat(){new Ajax.Request(url['heartbeat'],{method:'post',onComplete:function(){startHeartbeatTimer();},});}
function startHeartbeatTimer(){heartbeatTimer=setTimeout(heartbeat,heartbeatInterval*1000);}
Event.observe(window,'load',function(evt){url=url_for_op_chat;chatviewUpdated();Event.observe('chat_content','submit',function(evt){if($F('chat_content_body')!=''){post(Form.serialize('chat_content',true));}
else{update();}
Event.stop(evt);});startUpdateTimer();startUpdateMemberListTimer();startHeartbeatTimer();});}());
