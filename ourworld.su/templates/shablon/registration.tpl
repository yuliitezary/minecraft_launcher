<div class="block"><div class="block-head">
	[registration]Регистрация аккаунта<span style="float: right;">Шаг первый - Регистрация</span>[/registration]
	[validation]Регистрация аккаунта<span style="float: right;">Шаг третий - Завершение</span>[/validation]
</div>
[registration]
<div class="block-body">
<div  class="info_access">
Вы находитесь на странице регистрации аккаунта. Для того чтобы начать играть на нашем проекте, 
Вам необходимо выполнить три простых шага. 
</div>
<br/>
<div  class="info_warning">
При заполении формы регистрации введите желаемый никнейм, пароль и E-mail адрес, на который будет отправлено 
письмо с подтверждением регистрации. Если письмо с активацией долго не приходит, проверьте папку Спам. Ответственно отнеситесь к выбору своего ника, т.к. его нельзя будет изменить в дальнейшем. Удачи!
</div>
<br/>
[/registration]
[validation]
<div class="block-body">
<div  class="info_access">
Ваш E-mail адрес подтвержден. Завершите регистрацию Вашего аккаунта этим последним шагом, укажите дополнительную информацию ниже. 
Пожалуйста не пренебрегайте этим шагом, заполните все поля достоверными данными, они помогут нам связаться с Вами в случае необходимости.
</div>
[/validation]
<table class="reg_form">
	[registration]
		<tr>
			<td width="43%" class="label">
				<span class="reg_info">Придумайте ник</span><br/><span class="reg_info2">От трех до 16 символов</span>
			</td>
			<td>
				<span class="icon-tf icon-user"></span><input placeholder="Например: Sample" autocomplete="off" type="text" name="name" id='name' style="margin-right: 6px;" class="reg_tf" />
				<div id='result-registration'></div>
			</td>
		</tr>
		<tr>
			<td class="label">
				<span class="reg_info">Ваш пароль<br/><span class="reg_info2">Будет использован для входа</span>
			</td>
			<td><span class="icon-tf icon-lock"></span><input placeholder="Например: s1Am2Mp3E" autocomplete="off" type="password" name="password1" class="reg_tf" /></td>
		</tr>
		<tr>
			<td class="label">
				<span class="reg_info">Повторите пароль</span><br/><span class="reg_info2">Убедитесь, что не допустили ошибку</span>
			</td>
			<td><span class="icon-tf icon-lock"></span><input placeholder="Например: s1Am2Mp3E" autocomplete="off" type="password" name="password2" class="reg_tf" /></td>
		</tr>
		[ref]
			<tr>
				<td class="label">
					<span class="reg_info">Ник пригласившего игрока</span><br/><span class="reg_info2">Ник пригласившего на наш проект Вас друга</span>
				</td>
				<td><span class="icon-tf icon-user"></span><input placeholder="Например: Notch" autocomplete="off" type="text" name="ref_login" class="reg_tf" /></td>
			</tr>
		[/ref]
		<tr>
			<td class="label">
				<span class="reg_info">Ваш E-Mail</span><br/><span class="reg_info2">Сюда будет прислано подтверждение</span>
			</td>
			<td><span class="icon-tf icon-mail"></span><input placeholder="Например: sample@yandex.ru" autocomplete="off" type="text" name="email" class="reg_tf" /></td>
		</tr>
		[question]
		<tr>
			<td class="label">
				Вопрос:
			</td>
			<td>
				<div>{question}</div>
			</td>
		</tr>
		<tr>
			<td class="label">
				Ответ:<span class="impot">*</span>
			</td>
			<td>
				<div><input type="text" autocomplete="off" name="question_answer" class="reg_tf" /></div>
			</td>
		</tr>
		[/question]
[sec_code]
		<tr>
			<td class="label">
				{reg_code}
			</td>
			<td>
				<span class="icon-tf icon-arrow2"></span><input placeholder="Введите код с картинки" type="text" name="sec_code" class="reg_tf" />
			</td>
		</tr>
		[/sec_code]
		[recaptcha]
		<tr>
			<td class="label">
				Введите два слова, показанных на изображении:<span class="impot">*</span>
			</td>
			<td>
				<div>{recaptcha}</div>
			</td>
		</tr>
		[/recaptcha]
    
	[/registration]
	[validation]
		<tr>
			<td class="label"><span class="reg_info">Ваше настоящее имя</span></br><span class="reg_info2">Укажите ваше реальное имя</span></td>
			<td><span class="icon-tf icon-tag"></span><input placeholder="Например: Александр" type="text" name="fullname" class="reg_tf" /></td>
		</tr>
		<tr>
			<td class="label"><span class="reg_info">Место жительства</span></br><span class="reg_info2">Город, село, деревня и т.д.</span></td>
			<td><span class="icon-tf icon-location"></span><input placeholder="Например: Мурманск" type="text" name="land" class="reg_tf" /></td>
		</tr>
	[/validation]
	</table>
	<br/>
	<div align="center" class="fieldsubmit">
		<button style="width: 100%; margin-top: 15px;" name="submit" class="bbcodes" type="submit"><span>Отправить данные на обработку</span></button>
	</div>
</div>
</div>