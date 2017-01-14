<?php echo'<?xml version="1.0" encoding="windows-31j" ?>' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<?php
//	echo $html->css(array('admins_default', 'admins_index') , null, array('inline'=>true));
//	echo $html->script(array('jquery/jquery-1.4.2.min', 'jquery/plugin/jquery.corner', 'frontend/homes'), array('inline'=>true));
//	echo $html->meta('icon', '/img/favicon.ico');
?>
<?php
//	$html->css(array('public_default', 'users_regist') , null, array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min', 'jquery/plugin/jquery.corner', 'users_regist'), array('inline'=>false));
//	$html->script(array('jquery/jquery-1.4.2.min', 'jquery/plugin/jquery.corner', 'frontend/users_regist'), array('inline'=>false));

//	AppController::set('title_for_layout', 'ACURS(エクルス)：プロフィール登録');
?>
<body>
<div id="header_block">
<?php /* echo $this->element('header_block/header_public');*/ ?>
</div>
	<?php echo $form->create(null, array('type'=>'post', 'url'=>array('controller'=>'users', 'action'=>'regist'))); ?>
			<div id="application_block">
				<p>
					利用規約<br/>
					<textarea name="textarea" rows="7" readonly="readonly" cols="74"></textarea>
					<br/>
					<?php echo $form->checkbox('User.agreement', array('id'=>'check_agreement')); ?>
					<label for="check_agreement">規約に同意する</label>
					<br/><span class="validate_agreement"><?php echo $form->error('User.agreement'); ?></span>
				</p>
			</div>
			<div id="category_block">
				<p>
				<label class="subject">区分</label>
				<?php echo $form->radio('User.user_type', array('prsn'=>'個人', 'corp'=>'法人'), array('class'=>'radio', 'legend'=>false, 'value'=>'prsn')); ?>
				</p>
			</div>
			<br/>
			<div id="profil_block">
				<div class="corporation_element">
					<p>
					<?php echo $form->input('User.corporate_name', array('label'=>'社名', 'class'=>'m')); ?>
					<br/><span class="validate"><?php echo $form->error('User.corporate_name'); ?></span>
					</p>
					<p>
					<?php echo $form->input('User.section_name', array('label'=>'所属部署', 'class'=>'m')); ?>
					<br/><span class="validate"><?php echo $form->error('User.section_name'); ?></span>
					</p>
				</div>
				<p>
				<label class="individual_element">氏名</label>
				<label class="corporation_element">ご担当者氏名</label>
				<?php echo $form->text('User.user_name', array('class'=>'m')); ?>
				<span class="individual_element">
				<br/><span class="caption">ペンネームではなく本名でお願いします</span>
				</span>
				<br/><span class="validate"><?php echo $form->error('User.user_name'); ?></span>
				</p>
				<p>
				<?php echo $form->input('User.user_hn', array('label'=>'ニックネーム', 'type'=>'text', 'class'=>'m', 'error'=>false)); ?>
				<br/><span class="caption">違法サイトに関するフォーラムへ投稿いただく際に利用します</span>
				<br/><span class="validate"><?php echo $form->error('User.user_hn'); ?></span>
				</p>
				<p>
				<label>生年月日</label>
				<?php
					echo $exform->dateYMD('User.user_birthday', null , array(
																	'minYear'=>1900,
																	'maxYear'=>date('Y') - 15,
																	'separator'=>array(" 年 "," 月 "," 日 "),
																), true);

				?>
				<br/><span class="validate"><?php echo $form->error('User.user_birthday'); ?></span>
				</p>
				<p>
				<?php echo $form->input('User.tel', array('label'=>'電話番号', 'class'=>'s', 'error'=>false)); ?>
				<br/><span class="caption">半角英数字で、ハイフン( - )を除いてください</span>
				<br/><span class="validate"><?php echo $form->error('User.tel'); ?></span>
				</p>
				<p>
				<label>ログインＩＤ</label>
				<?php echo $form->text('User.user_id', array('class'=>'m')); ?>
				<!-- <span id="id_check" class="corner">使用可能なＩＤかチェック</span> -->
				<br/><span class="caption">4～31文字の半角英数字でお願いします</span>
				<br/><span class="validate"><?php echo $form->error('User.user_id'); ?></span>
				</p>
				<p>
				<label>パスワード</label>
				<?php echo $form->text('User.password', array('class'=>'m', 'error'=>false)); ?>
				<br/><span class="caption">5文字以上の半角英数字でお願いします</span>
				<br/><span class="validate"><?php echo $form->error('User.password'); ?></span>
				</p>
				<p>
				<?php echo $form->input('User.mail_address', array('label'=>'メールアドレス', 'class'=>'l', 'error'=>false)); ?>
				<br/><span class="validate"><?php echo $form->error('User.mail_address'); ?></span>
				</p>
				<p>
				<?php echo $form->input('User.mail_address_confirm', array('label'=>'メールアドレス確認', 'class'=>'l', 'error'=>false)); ?>
				<br/><span class="validate"><?php echo $form->error('User.mail_address_confirm'); ?></span>
				</p>
				<p>
				<?php echo $form->input('User.mail_address_confirm', array('label'=>'郵便番号', 'class'=>'l', 'error'=>false)); ?>
				<br/><span class="validate"><?php echo $form->error('User.mail_address_confirm'); ?></span>
				</p>
				<p>
				<?php echo $form->input('User.mail_address_confirm', array('label'=>'住所', 'class'=>'l', 'error'=>false)); ?>
				<br/><span class="validate"><?php echo $form->error('User.mail_address_confirm'); ?></span>
				</p>
			</div>
			<div id="submit_block">
				<?php echo $form->submit('登録する', array('class'=>'submit_btn')); ?>
			</div>
		<?php echo $form->end(); ?>
		</div><!--inline-->
	</div><!--outline-->
</div>
<div id="footer_block">
<?php /* echo $this->element('footer_block/footer'); */ ?>
</div>
</body>