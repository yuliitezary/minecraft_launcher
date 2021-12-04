<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="<?php echo $lk->path?>templates/<?php echo $lk->cfg['template']?>/js/class/req.class.js"></script>
		<script type="text/javascript" src="<?php echo $lk->path?>templates/<?php echo $lk->cfg['template']?>/js/class/anim.class.js"></script>
		<script type="text/javascript" src="<?php echo $lk->path?>templates/<?php echo $lk->cfg['template']?>/js/lk.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $lk->path?>templates/<?php echo $lk->cfg['template']?>/style/style.css">
		
		<script type="text/javascript">
			var lk = new _lk( '<?php echo $lk->user['key']?>', '<?php echo $lk->path?>ajax.php', <?php echo $lk->cfg['anti_ddos'] != false ? $lk->cfg['anti_ddos'] : 'false'?> );
			
			lk.username = '<?php echo $lk->user['name']?>', lk.money = <?php echo $lk->user['money']?>, lk.icmoney = <?php echo ($lk->user['icmoney'] != false ? $lk->user['icmoney'] : 'false')?>;
			lk.path = '<?php echo $lk->path?>';
			
			<?php
				for( $i = 0, $Max = count($lk->cfg['server']); $i < $Max; $i ++ ) {
					echo 'lk.server[' . $i . '] = new Array(' . $lk->user['status'][$i][0] . ', ' . $lk->user['status'][$i][1] * 1000 . ', \'' . $lk->cfg['server'][$i]['name'] . '\', new Array(' . $lk->user['prefix'][$i][0] . ', \'' . $lk->user['prefix'][$i][1] . '\', ' . $lk->user['prefix'][$i][2] . ', ' . $lk->user['prefix'][$i][3] . '), '. $lk->getUnbanPrice($i) .');' . "\n";
				}
				
				for( $i = 0, $Max = count($lk->cfg['status']); $i < $Max; $i ++ ) {
					echo 'lk.status[' . $i . '] = new Array(\'' . $lk->cfg['status'][$i]['name'] . '\', \'' . $lk->cfg['status'][$i]['desc'] . '\', ' . $lk->cfg['status'][$i]['price'] . ', ' . $lk->cfg['status'][$i]['buy_days'] . ', ' . $lk->cfg['status'][$i]['set_days'] . ');' . "\n";
				}
			
				for( $i = 0, $Max = count($lk->cfg['cur']); $i < $Max; $i ++ ) {
					echo 'lk.cur[' . $i . '] = \''. $lk->cfg['cur'][$i] . '\';' . "\n";
				}
				
				echo 'lk.prefix[0] = '. $lk->cfg['prefix']['prefix_min_len'] .';' . "\n";
				echo 'lk.prefix[1] = '. $lk->cfg['prefix']['prefix_max_len'] .';' . "\n";
				echo 'lk.iconomy[1] = '. $lk->cfg['exchange']['iconomy']['price_u_cur'] .';' . "\n";
			?>
		</script>
	</head>
	
	<body>
		<div id="alert_bg_dark" style="display: none"></div>
		
		<div class="lk">
			<div class="lk-menu">
				<div class="lk-menu-image"> 
					<!--<span class="lk-menu-image-text"><?php echo $lk->user['name']?></span>-->
				</div>
				
				<div class="lk-menu-nav">
					<a onclick="lk.menu(1)" id="menu-1" class="lk-menu-nav-active" style="width: 98px;">ИНФОРМАЦИЯ</a><a onclick="lk.menu(2)" id="menu-2">СТАТУСЫ И ПРЕФИКСЫ</a><a onclick="lk.menu(3)" id="menu-3">ОБМЕН</a><a onclick="lk.menu(4)" id="menu-4">ПОПОЛНИТЬ СЧЕТ</a><a onclick="lk.menu(5)" id="menu-5">ДРУГОЕ</a>
				</div>
			</div>
			
			
			<div class="lk-body" id="lk-body">
				<div id="lk-body-alert" style="display: none" onclick="lk.anim.hide(this)" title="Кликните, чтобы закрыть."></div>
				<div id="lk-body-alert_window" style="display: none">
					<div class="lk-body-alert_window-head" onmousedown="lk.alertMove(this)" onselectstart="return false">
						<span></span>
						
						<span style="float: right;">
							<span onclick="lk.alert_window_fullscreen()" class="lk-body-alert_window-head-icon" style="margin-right: 10px;">[]</span>
							<span onclick="lk.alert_window_close()" class="lk-body-alert_window-head-icon">X</span>
						</span>
					</div>
					<div class="lk-body-alert_window-content"></div>
				</div>
				
				<?php if ( $lk->cfg['other']['unban']['unban_all'] && $lk->user['ban']['name'] != false ) { ?>
					<div id="lk-body-alert" style="background: #FFADAD;border: 1px solid #EC5C5C;" onclick="lk.anim.hide(this)" title="Кликните, чтобы закрыть.">Вы бали забанены на данном проекте по при причине: <b><?php echo $lk->user['ban']['reason']?></b></div>
				<?php } ?>
				
				<div id="lk-body-block-1" style="display: block;">
					<div class="lk-body-block-1-info">
						<table>
							<tr>
								<td width="150"><b>ID</b></td>
								<td width="250"><?php echo $lk->user['id']?></td>
							</tr>
							
							<tr>
								<td><b>Никнейм</b></td>
								<td><?php echo $lk->user['name']?></td>
							</tr>
							
							<tr>
								<td><b>Денег</b></td>
								<td id="lk-money-1"><span class="lk-cur-image" title="<?php echo $lk->cfg['cur'][4]?>"></span> <?php echo $lk->user['money'] . ' ' . $lk->getNameCur( $lk->user['money'] )?></td>
							</tr>
							
							
							<?php if ( $lk->user['icmoney'] != false ) {?>
								<tr>
									<td><b>Монет iConomy</b></td>
									<td id="lk-icmoney-1"><span class="lk-cur-iconomy-image" title="монета iConomy"></span> <?php echo $lk->user['icmoney']?> монет</td>
								</tr>
							<?php } ?>
							
							<tr>
								<td><b>Разбанен платно</b></td>
								<td id="lk-money-1"><?php echo $lk->getCountUnbanAll()?> раз</td>
							</tr>
						</table>
						
						<div id="lk-rights">
							<?php if ( $lk->cfg['rights']['enable'] ) { echo $lk->outputRights( 'rights' ); } ?>
						</div>
					</div>
					
					<div class="lk-body-block-1-skin" align="center">
						<div onmouseover="lk.skin(1)" onmouseout="lk.skin(2)">
							<div id="lk-body-block-1-skin-1">
								<img src="<?php echo $lk->path?>skin.php?username=<?php echo $lk->user['name']?>&update=0" alt="Пожалуйста, подождите..."/>
								<img src="<?php echo $lk->path?>skin.php?username=<?php echo $lk->user['name']?>&mode2=1&mode=1&update=0"/>
							</div>
							
							<div id="lk-body-block-1-skin-2" style="display: none">
								<img src="<?php echo $lk->path?>skin.php?username=<?php echo $lk->user['name']?>&mode=1&update=0" alt="Пожалуйста, подождите..."/>
								<img src="<?php echo $lk->path?>skin.php?username=<?php echo $lk->user['name']?>&mode2=1&update=0"/>
							</div>
						</div>
						
						<div class="lk-body-block-1-skin-form">
							<input type="file" id="lk-body-block-1-skin-upload_skin" name="skin"><br/><br/>
							<div align="left" id="lk-progress-upload" style="display: none; width: 200px" class="lk-bar-1"><div align="center" style="width: 50%;"></div></div>
							<input type="button" onclick="lk.upload_skin(1)" style="width: 200px" class="lk-button-1" value="Загрузить скин"><br/>
							<input type="button" onclick="lk.upload_skin(2)" style="width: 200px" class="lk-button-1" value="Загрузить плащ"><br/>
						</div>
					</div>
					
					<br style="clear: both;"/>
				</div>
				
				<div id="lk-body-block-2" style="display: none;">
					<h3 class="lk-body-block-head">Покупка статуса/префикса</h3>
					
					<p align="center">Выберите сервер</p>
					<div class="lk-body-block-2-servers" id="lk-body-block-2-servers">
						<?php echo $lk->outputServers('servers_select')?>
					</div>
					
					
					<div class="lk-body-block-2-server-opt" id="lk-body-block-2-server-opt" style="display: none;">
						<div class="lk-body-block-2-server-opt-prefix">
							Префикс
							
							<div class="lk-body-block-2-server-opt-prefix-block">
								<div id="lk-body-block-2-server-opt-prefix-inputes">
									<select class="lk-input_text-1">
										<option style="background:#ffffff;" value="0">#f</option>
										<option style="background:#000000; color: #fff;" value="1">#0</option>
										<option style="background:#0000bf;" value="2">#1</option>
										<option style="background:#00bf00;" value="3">#2</option>
										<option style="background:#00bfbf;" value="4">#3</option>
										<option style="background:#bf0000;" value="5">#4</option>
										<option style="background:#bf00bf;" value="6">#5</option>
										<option style="background:#bfbf00;" value="7">#6</option>
										<option style="background:#bfbfbf;" value="8">#7</option>
										<option style="background:#404040;" value="9">#8</option>
										<option style="background:#4040ff;" value="10">#9</option>
										<option style="background:#40ff40;" value="11">#a</option>
										<option style="background:#40ffff;" value="12">#b</option>
										<option style="background:#ff4040;" value="13">#c</option>
										<option style="background:#ff40ff;" value="14">#d</option>
										<option style="background:#ffff40;" value="15">#e</option>
									</select>
									<input type="text" class="lk-input_text-1" style="width: 70px;" placeholder="Текст">
									<select class="lk-input_text-1">
										<option style="background:#ffffff;" value="0">#f</option>
										<option style="background:#000000; color: #fff;" value="1">#0</option>
										<option style="background:#0000bf;" value="2">#1</option>
										<option style="background:#00bf00;" value="3">#2</option>
										<option style="background:#00bfbf;" value="4">#3</option>
										<option style="background:#bf0000;" value="5">#4</option>
										<option style="background:#bf00bf;" value="6">#5</option>
										<option style="background:#bfbf00;" value="7">#6</option>
										<option style="background:#bfbfbf;" value="8">#7</option>
										<option style="background:#404040;" value="9">#8</option>
										<option style="background:#4040ff;" value="10">#9</option>
										<option style="background:#40ff40;" value="11">#a</option>
										<option style="background:#40ffff;" value="12">#b</option>
										<option style="background:#ff4040;" value="13">#c</option>
										<option style="background:#ff40ff;" value="14">#d</option>
										<option style="background:#ffff40;" value="15">#e</option>
									</select>
									<select class="lk-input_text-1">
										<option style="background:#ffffff;" value="0">#f</option>
										<option style="background:#000000; color: #fff;" value="1">#0</option>
										<option style="background:#0000bf;" value="2">#1</option>
										<option style="background:#00bf00;" value="3">#2</option>
										<option style="background:#00bfbf;" value="4">#3</option>
										<option style="background:#bf0000;" value="5">#4</option>
										<option style="background:#bf00bf;" value="6">#5</option>
										<option style="background:#bfbf00;" value="7">#6</option>
										<option style="background:#bfbfbf;" value="8">#7</option>
										<option style="background:#404040;" value="9">#8</option>
										<option style="background:#4040ff;" value="10">#9</option>
										<option style="background:#40ff40;" value="11">#a</option>
										<option style="background:#40ffff;" value="12">#b</option>
										<option style="background:#ff4040;" value="13">#c</option>
										<option style="background:#ff40ff;" value="14">#d</option>
										<option style="background:#ffff40;" value="15">#e</option>
									</select>
								</div>
								
								<p align="right">
									<input type="button" onclick="lk.prefix()" class="lk-button-1" value="Сделать префикс">
								</p>
							</div>
						</div>
						
						<div class="lk-body-block-2-server-opt-status" id="lk-body-block-2-server-opt-status">
							Статусы
							<?php echo $lk->outputStatuses('statuses')?>
						</div>
						
						<!--
							<p>Приобретение статуса</p>
							<input type="text" class="lk-input_text-1" placeholder="На сколько дней"> <input type="button" onclick="lk.upload_skin(2)" class="lk-button-1" value="Купить статус">
						-->
					</div>
					<br style="clear: both;"/>
				</div>
				
				<div id="lk-body-block-3" style="display: none;">
					
					<?php if ( $lk->cfg['exchange']['iconomy']['enable'] ) { ?>
						<?php if ( $lk->cfg['exchange']['iconomy']['price_u_cur'] ) { ?>
						
							<div class="lk-body-block-3-block">
								<p>Монеты iConomy <?php echo ($lk->user['icmoney'] != false ? '(<span id="lk-icmoney-3">'. $lk->user['icmoney'] .' монет</span>)' : '')?> <span class="lk-exchange-image"></span> Деньги ЛК (<span id="lk-money-3"><?php echo $lk->user['money'] . ' ' . $lk->cfg['cur'][1] . '.'?></span>)</p>
								<?php if ( $lk->user['icmoney'] == false ) { ?>
									<select class="lk-input_text-1">
										<?php echo $lk->outputServerAsOption()?>
									</select>
								<?php } ?>
								<input type="text" class="lk-input_text-1" onkeyup="if ( (this.value * this.value) && this.value > 0 ) this.nextElementSibling.value = 'Обменять '+ Math.ceil(this.value * lk.iconomy[1]) +' монет на '+ this.value +' '+ lk.cur[1]" placeholder="Кол-во денег ЛК">
								<input type="button" onclick="lk.exchange_iconomy(this.previousElementSibling.previousElementSibling.value, 1, this.previousElementSibling.value)" class="lk-button-1" value="Обменять">
								<p>
									Курс: 1<span class="lk-cur-image" title="<?php echo $lk->cfg['cur'][4]?>"></span> <b>=</b> <?php echo $lk->cfg['exchange']['iconomy']['price_u_cur']?><span class="lk-cur-iconomy-image" title="монета iConomy"></span>
								</p>
							</div>
							
						<?php } ?>
					<?php } ?>
				</div>
				
				<div id="lk-body-block-4" style="display: none;">
					
					<h3>Пополнение баланса ЛК</h3>
					<div class="lk-body-block-3-block">
						<form method="<?php echo $lk->cfg['payment']['method']?>" action="<?php echo $lk->path . $lk->cfg['payment']['path']?>">
							<input type="text" onkeyup="this.parentElement.lastElementChild.value = 'Далее ' + (this.value * <?php echo $lk->cfg['cur_price']?>).toFixed(1) + ' <?php echo $lk->cfg['payment']['curname']?>'" style="width: 100px" name="<?php echo $lk->cfg['payment']['params'][$lk->cfg['payment']['type'] - 1]['sum']?>" class="lk-input_text-1" placeholder="Введите сумму" value="1"/>
							<input type="hidden" name="<?php echo $lk->cfg['payment']['params'][$lk->cfg['payment']['type'] - 1]['cur']?>" value="<?php echo $lk->cfg['payment']['cur']?>"/>
							<input type="hidden" name="<?php echo $lk->cfg['payment']['params'][$lk->cfg['payment']['type'] - 1]['user']?>" value="<?php echo $lk->user['id']?>"/>
							<input type="hidden" name="<?php echo $lk->cfg['payment']['params'][$lk->cfg['payment']['type'] - 1]['desc']?>" value="<?php echo $lk->cfg['payment']['desc']?>"/>
							<input type="hidden" name="pay_system" value="<?php echo $lk->cfg['payment']['type']?>"/>
							<span class="lk-cur-image" title="<?php echo $lk->cfg['cur'][4]?>"></span>
							<input type="submit" class="lk-button-1" style="float: right" value="Далее"/>
						</form>
						
						<p align="center">Здесь можно произвести пополнение счета в Личном Кабинете!<br/>Курс: <b>1<span class="lk-cur-image" title="<?php echo $lk->cfg['cur'][4]?>"></span> = <?php echo $lk->cfg['cur_price'] . ' ' . $lk->cfg['payment']['curname']?></b></p>
					</div>
				</div>
				
				<div id="lk-body-block-5" style="display: none;">
					<?php if ( $lk->cfg['other']['unban']['enable'] ) { ?>
						<div class="lk-body-block-3-block">
							<p>Разбан</p>
							
							<p align="center" id="lk-unban-inputs">
								<?php if ( !$lk->cfg['other']['unban']['unban_all'] ) { ?>
								
									<select class="lk-input_text-1" onchange="this.nextElementSibling.firstElementChild.value = lk.server[this.value][4]">
										<?php echo $lk->outputServerAsOption()?>
									</select>
									<button class="lk-button-1" onclick="lk.unban(this.previousElementSibling.value)">Разбаниться за <span><?php echo $lk->getUnbanPrice(0)?></span> <?php echo $lk->cfg['cur'][1]?>.</button>
									
								<?php } else { ?>
									
									<?php echo $lk->user['ban']['name'] == false ? 'Вы не забанены' : 'Вас забанил администратор <b>' . $lk->user['ban']['admin'] . '</b> '. date('d.m.Y', $lk->user['ban']['time']) .' по следующей причине:<br/>' . $lk->user['ban']['reason']?><br/>
									<button class="lk-button-1" onclick="lk.unban(-1)">Разбаниться за <?php echo $lk->getUnbanPrice(0) . ' ' . $lk->cfg['cur'][1]?>.</button>
									
								<?php } ?>
							</p>
						</div>
					<?php } ?>
					
					
					<?php if ( $lk->cfg['other']['vaucher']['enable'] ) { ?>
						<div class="lk-body-block-3-block">
							<p>Ваучер</p>
							
							<p align="center">
								<input type="text" class="lk-input_text-1" placeholder="Ваучер">
								<button class="lk-button-1" onclick="lk.vaucher(this.previousElementSibling.value)">Проверить!</button>	
							</p>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<script>lk.init();</script>
	</body>
</html>