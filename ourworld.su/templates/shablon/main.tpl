<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ru">
<head>
{headers}
<link rel="stylesheet" href="{THEME}/style/site.css" type="text/css">
<link rel="stylesheet" href="{THEME}/style/icon.css" type="text/css">
<link rel="stylesheet" href="{THEME}/style/slider.css" type="text/css">
<link rel="stylesheet" href="{THEME}/style/animate.css" type="text/css">

<script type="text/javascript" src="{THEME}/js/smooth-scroll.js"></script>
<script type="text/javascript" src="{THEME}/js/tooltip.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="{THEME}/js/libs.js" type="text/javascript"></script>
<script src="http://vk.com/js/api/openapi.js" type="text/javascript"></script>
<script src="https://vk.com/js/api/openapi.js?161" type="text/javascript"></script>
</head>
<body id="body">
{AJAX}
<script type="text/javascript">
jQuery(function($) { 
  var $slider = $('.slider'); // class or id of carousel slider
  var $slide = 'li'; // could also use 'img' if you're not using a ul
  var $transition_time = 1000; // 1 second
  var $time_between_slides = 4000; // 4 seconds
  function slides(){
  return $slider.find($slide);
  }
  slides().fadeOut();
  slides().first().addClass('active');
  slides().first().fadeIn($transition_time);
  $interval = setInterval(
    function(){
      var $i = $slider.find($slide + '.active').index();

      slides().eq($i).removeClass('active');
      slides().eq($i).fadeOut($transition_time);

      if (slides().length == $i + 1) $i = -1; // loop to start

        slides().eq($i + 1).fadeIn($transition_time);
          slides().eq($i + 1).addClass('active');
    }
    , $transition_time +  $time_between_slides 
  );
});
</script>    

[group=5]
<div id="noreg-warning" class="container-noreg">
Уважаемые посетители нашего проекта, некоторые функции нашего сайта не доступны неавторизированным пользователям, создайте аккаунт, чтобы снять запрет.
<div class="noreg-warning-close"><a onclick="$('div#noreg-warning').hide()"><span class="icon-cross"></span></a></div>
    </div>
[/group]
<div id="container">
<div id="auth_header">
<input class="logo" value="" onclick="location.href='/'" type="button"/>
[group=5]
<div class="togame">
    <a href="/?do=register"><div class="togame_reg"><span class="icon-plus3" style="padding-right: 15px;"></span>Зарегистрироваться</div></a>
</div>
[/group]
[not-group=5]
<div class="togame">
    <a href="/download.html"><div class="togame_but"><span class="icon-download" style="padding-right: 15px;"></span>Загрузить лаунчер</div></a>
</div>
[/not-group]
</div>

[aviable=forum]
<div id="left_cont" style="width: 100% !important;">
[/aviable]
[not-aviable=forum]
<div id="left_cont">
[/not-aviable]
<div class="news_pad">
    <ul class="nav">
        [group=5]
        <li><a href="/"><span class="icon-forward"></span>Главная</a></li>
		[/group]
		[not-group=5]
        <li><a href="/"><span class="icon-forward"></span>Главная</a></li>
		[/not-group]
        <li><a href="#"><span class="icon-list2"></span>Сервера</a>
            <i class="triangle"></i>
             <ul class="sub-nav">
                <li><a href="/server1.html">OurWorld <div class="right">1.12.2</div></a></li>
                <li><a href="#">Destbolum <div class="right">?</div></a></li>
				[group=5]
				<a href="/download.html">Загрузить лаунчер</a>
				</li>
				[/group]
				[not-group=5]
				<li class="nav-launcher-download">
				<a href="/download.html">Загрузить лаунчер</a>
				</li>
				[/not-group]
            </ul>
        </li>
		[group=5]
        <li class="nav-disable hint" data-original-title="Необходимо зарегистрироваться для доступа к личному кабинету."><a href="/cabinet.html"><span class="icon-suitcase" ></span> Личный кабинет</a></li>
		[/group]
		[not-group=5]
        <li><a href="/cabinet.html"  ><span class="icon-suitcase"></span>Личный кабинет</a></li>
		[/not-group]
		<li><a href="#"><span class="icon-lifebuoy"></span>Поддержка</a>
            <i class="triangle"></i>
            <ul class="sub-nav">
				<li><a href="/banlist/">Бан-лист проекта</a></li>
                <li><a href="/contacts.html">Связь с администрацией</a></li>
                <li><a href="/faq/">FAQ по проекту</a></li>
                <li><a href="/commands/">Команды серверов</a></li>
                <li><a href="/team/">Команда проекта</a></li>
            </ul>
        </li>
		<li><a href="/rules/" ><span class="icon-graduation"></span>Правила</a></li>
		[aviable=forum]
		[not-group=5]
		<li style="float: right;"><a href="#"><span class="icon-cog"></span> Опции</a>
		    <i class="triangle"></i>
            <ul class="sub-nav">
                <li><a href="/user/{user}">Профиль</a></li>
                <li><a href="/message/">Сообщения </a></li>
                <li><a href="/logout/">Выход</a></li>
            </ul>
		</li>
		[/not-group]
		[/aviable]
    </ul>
</div>
{info}
[aviable=cat|main]
<!-- 
[group=5]
<ul class="slider" style="height: 300px !important;">
  <li>
    <img src="/templates/shablon/images/slider/slider-1.jpg">
  </li>
  <li>
    <img src="/templates/shablon/images/slider/slider-2.jpg">
  </li>
  <li>
    <img src="/templates/shablon/images/slider/slider-3.jpg">
  </li>
  <li>
    <img src="/templates/shablon/images/slider/slider-4.jpg">
  </li>
</ul>
[/group]
[not-group=5]
<ul class="slider" style="height: 300px !important;">
  <li>
    <img src="/templates/shablon/images/slider/slider-1.jpg">
  </li>
  <li>
    <img src="/templates/shablon/images/slider/slider-2.jpg">
  </li>
  <li>
    <img src="/templates/shablon/images/slider/slider-3.jpg">
  </li>
</ul>
[/not-group]
-->
[/aviable]
[aviable=forum]
[group=5]
<div class="mes_warning">
Чтобы просматривать форум без запретов и видеть все существующие разделы и темы, а также отвечать в темах и создавать свои необходимо авторизироваться.
</div>
[/group]
[/aviable]
{content}
</div>
[not-aviable=forum]
<div id="right_cont">
	{login}
	[not-group=5]
	<div class="left_block_rate">
	<a class="hint" data-original-title="При голосование на Ваш счет будет зачислен 1 руб." href="http://mcrate.su/rate/4926"><img src="/templates/shablon/images/rate/mcrate.jpg"></a>
	<a class="hint" data-original-title="При голосование на Ваш счет будет зачислен 1 руб." href="http://mctop.im/vote/463"><img src="/templates/shablon/images/rate/mctop.jpg"/></a>
	<div class="clear"></div>
	</div>
	[/not-group]
	<div class="left_block">
		<div class="left_block_head">
		<span class="icon-bars"></span> Мониторинг
		</div>
			<div class="left_block_cont">
				{include file="/mon/index.php"}
			</div>
	</div>
	<div class="left_block">
		<div class="left_block_head">
		<span class="icon-paperplane"></span> Следуйте за нами...
		</div>
			<div class="left_block_cont">
				<div id="vk_groups"></div>
				
			</div>
	</div>
	</div>
[/not-aviable]
	<div style="clear: left;"></div>
	<div class="footer">
        <!--<div class="copyright"><b>Powered by <a target="_blank" href="/engine/go.php?url=aHR0cDovL25ld3RlbXBsYXRlcy5ydS8%3D">DataLife Engine</a></b> © 2014-2016</br></div>-->
	<div class="about"><span><a href="/team">Наша команда</a></span> | <span><a href="/contacts">Контакты</a></span></div>
	<div class="clear"></div>
	</div>
</div>
</body>
</div>
</html>