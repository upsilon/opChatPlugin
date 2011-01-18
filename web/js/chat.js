var Chat=Class.create({config:{updateInterval:5,updateMemberListInterval:60,heartbeatInterval:30,url:{},sounds:{}},initialize:function(config){config=config||{};this.config=$H(this.config).merge($H(config)).toObject();this.updateTimer=null;this.updateMemberListTimer=null;this.heartbeatTimer=null;this.lastID=0;var html='<dt>'
+'<span class="number">#{number}</span>: '
+'<a href="#{member_url}" target="_blank">#{member_name}</a> '
+'#{created_at}'
+'</dt>'
+'<dd class="#{command}">#{body}</dd>';this.contentTemplate=new Template(html);html='<dd id="restart">'
+'<a href="javascript:void(0)" id="restartLink">再接続する</a>'
+'</dd>';this.restartLink=html;Ajax.Responders.register({onException:this.timerStop.bind(this),onComplete:function(req){if(!req.success())
this.timerStop();}.bind(this)});Event.observe(window,'load',this.onLoad.bind(this));},onLoad:function(evt){if(!this.isSoundConfigEmpty())
$('config_sound').show();if(this.getSoundEnabled())
$('is_enable_sound').checked='checked';this.checkLastID();this.scroll($('chatview'));Event.observe('chat_content','submit',this.onSubmit.bind(this));Event.observe('is_enable_sound','change',this.onChangeSoundConfig.bind(this));this.timerStart();},onSubmit:function(evt){if($F('chat_content_body')!=''){var param=Form.serialize('chat_content',true);$('chat_content_body').setValue('');this.post(param);}
Event.stop(evt);},onChangeSoundConfig:function(evt){this.setSoundEnabled($F('is_enable_sound'));},onRestartLinkClick:function(evt){this.timerStart();Event.stop(evt);},setSoundEnabled:function(enabled){if(enabled)
localStorage['chat_sound_enabled']='true';else
localStorage['chat_sound_enabled']='false';},getSoundEnabled:function(){if(this.isSoundConfigEmpty())
return false;if(localStorage['chat_sound_enabled']!='true')
return false;return true;},isSoundConfigEmpty:function(){for(var i in this.config.sounds)
return false;return true;},checkLastID:function(){var num=$('chatview').getElementsByClassName('number');if(num.length==0)return;this.lastID=parseInt(num[num.length-1].innerHTML);},scroll:function(obj){obj.scrollTop=999999;},playSound:function(name){if(this.getSoundEnabled()&&this.config.sounds[name])
Sound.play(this.config.sounds[name],{replace:true});},chatviewUpdated:function(response){var json=response.responseJSON;if(!json||json.length==0){return;}
var html='';var maxLevel=0;json.each(function(content){content.number=parseInt(content.number);content.level=parseInt(content.level);if(content.number<=this.lastID){return;}
this.lastID=content.number;if(maxLevel<content.level){maxLevel=content.level;}
html+=this.contentTemplate.evaluate(content);},this);if(maxLevel>=5){switch(maxLevel){case 9:this.playSound('in');break;case 10:this.playSound('out');break;default:this.playSound('notice');break;}}
$('chatview').innerHTML+=html;this.scroll($('chatview'));},update:function(){new Ajax.Request(this.config.url['show'],{method:'get',parameters:{view:'chat',last:this.lastID},onSuccess:function(response){this.chatviewUpdated(response);this.startUpdateTimer();}.bind(this)});},startUpdateTimer:function(){this.updateTimer=setTimeout(this.update.bind(this),this.config['updateInterval']*1000);},stopUpdateTimer:function(){clearTimeout(this.updateTimer);},updateMemberList:function(){new Ajax.Updater({success:'memberlist'},this.config.url['show'],{method:'get',parameters:{view:'member'},onSuccess:this.startUpdateMemberListTimer.bind(this)});},startUpdateMemberListTimer:function(){this.updateMemberListTimer=setTimeout(this.updateMemberList.bind(this),this.config['updateMemberListInterval']*1000);},post:function(param){this.stopUpdateTimer();param['last']=this.lastID;new Ajax.Request(this.config.url['post'],{method:'post',parameters:param,onSuccess:function(response){this.chatviewUpdated(response);}.bind(this),onComplete:function(response){this.startUpdateTimer();}.bind(this)});},heartbeat:function(){new Ajax.Request(this.config.url['heartbeat'],{method:'post',onSuccess:this.startHeartbeatTimer.bind(this)});},startHeartbeatTimer:function(){this.heartbeatTimer=setTimeout(this.heartbeat.bind(this),this.config['heartbeatInterval']*1000);},timerStop:function(){clearTimeout(this.updateTimer);clearTimeout(this.updateMemberListTimer);clearTimeout(this.heartbeatTimer);if(!$('restartLink')){$('chatview').innerHTML+=this.restartLink;Event.observe('restartLink','click',this.onRestartLinkClick.bind(this));this.scroll($('chatview'));}},timerStart:function(){try{$('restart').remove();}
catch(e){}
this.startUpdateTimer();this.startUpdateMemberListTimer();this.startHeartbeatTimer();}});
