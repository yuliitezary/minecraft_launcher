[not-group=5]
		<div class="auth">
		<div class="auth_pad">
		</div>
		<div class="prof_head">
		</div>
		<div class="prof_cont">
		<div class="avatar">
        	<a href="{profile-link}"><img src="/cabinet/upload/avatar.php?s=50&u={login}" alt=""/></a>    
		<a href="{profile-link}"></a>
		<div>Привет, {login}!<br/>
		<span style="color: #8b8b8b;"><a href="/index.php?do=pm">Твои сообщения</a></span><span class="num_mes">{new-pm}</span></div></div>
		<div class="profcontent">
			<ul class="proflist">
			[group=1]<li><span class="icon-tools"></span><a href="/admin.php" target="_blank">Панель администратора</a></li>[/group]
			<li><span class="icon-tools"></span><a href="/cabinet.html">Личный кабинет</a></li>
               <li><span class="icon-vcard"></span><a href="{profile-link}">Настройки</a></li>
			<!--<li><span class="icon-cart"></span><a href="#">Онлайн-магазин</a></li>
			<li><span class="icon-ticket"></span><a href="#">Лотерея</a></li>
			<li><span class="icon-cycle"></span><a href="#">Реферальная система</a></li>
			<li><span class="icon-tag"></span><a href="#">Активация промо-кодов</a></li>
			<li><span class="icon-bars"></span><a href="#">Рейтинг игроков</a></li>-->
			<li><span class="icon-logout"></span><a href="/index.php?action=logout">Выход</a></li>
			</ul>
		</div>
		<!--<div class="balance">
			<div class="bal_text">ВАШ БАЛАНС</div>
			<div class="bal_amou">{real_money} руб.</br> {prem_money} куб.</div>
			<div style="clear: right;"></div>
			<a href="/index.php?do=lk&module=money">
			<input class="balance_but" value="ПОПОЛНИТЬ СЧЕТ" type="button"/>
			</a>
		</div>-->
        <br>
		</div>
		<div class="prof_foot">
		</div>
		</div>
[/not-group]
[group=5]
		<div class="auth">
		<div class="auth_pad">
		</div>
		<div class="auth_head">
		</div>
		<div class="auth_cont">
		<form method="post" action="">
		<div class="auth_desc"><span>Добро Пожаловать!</span> </br>Чтобы полноценно пользоваться всеми функциями сайта вам необходимо войти в свой аккаунт или зарегистрироваться.</div>
		<input for="login_name" placeholder="Введите ваш логин в поле" type="text" class="auth_tf " name="login_name" id="login_name" /></br>
		<input for="login_password" placeholder="Введите ваш пароль в поле" type="password" class="auth_tf " name="login_password" id="login_password" /></br>
		<div class="auth_pi"><a href="{lostpassword-link}">(Забыли пароль?)</a></div>
		<button class="cursor auth_ok" onclick="submit();" type="submit" title="Войти"><span>Войти</span></button>
		<div class="line_or">ИЛИ</div>
		<input class="cursor auth_reg" value="ЗАРЕГИСТРИРОВАТЬСЯ" onClick="location.href='/?do=register'" type="button"/>
		<input name="login" type="hidden" id="login" value="submit" />
		</form>
		</div>
		<div class="auth_foot">
		</div>
		</div>
[/group]