<?php require(APP_TEMPLATE_DIR."parts/header.php");?>
<div class="waku">
<a href="<?php echo APP_FILENAME; ?>">�Ǽ��Ĥˤ�ɤ�</a>
</div><br>
<div class="waku">
<?php

$SELF = APP_FILENAME;

if ($mailcount == 0) {
    echo <<<EOM
�᡼��ˤ����ƤϤ���ޤ���Ǥ�����<br>
�᡼�������ޤǿ�ʬ�����뤳�Ȥ⤢��ޤ���
EOM;
} else {
    echo <<<EOM
${mailcount}�����Ƥ�񤭹��ߤޤ�����
EOM;
}

?>
<hr>
<span style="font-weight:bold;color:blue"><?php echo APP_MAIL_ADDR; ?></span> �˷��ӥ᡼�����������ȡ�
���ηǼ��Ĥ���ƤǤ��ޤ���<br>
�᡼�����̾��̾���ˤʤꡢ<br>
�᡼��Υ�å����������Τޤޥ�å������ˤʤ�ޤ���<br>
<hr>
<a href="javascript:history.go(-1)">��ɤ�</a>
</div>

<?php require(APP_TEMPLATE_DIR."parts/footer.php");?>