<?php require(APP_TEMPLATE_DIR."parts/header.php");?>
<div class="waku">
<a href="<?php echo APP_FILENAME; ?>">�Ǽ��Ĥˤ�ɤ�</a>
</div><br>
<div class="waku">
<?php

$SELF = APP_FILENAME;

echo <<<EOM
<span style="font-size:14px;font-weight:bold">Ž���դ�����</span>
EOM;

$euc_tag = "<a href=\"${SELF}?m=jtag\">EUC-JP�ڡ�����</a>";
$sjis_tag= "<a href=\"${SELF}?m=jtag&c=s\">Shift_JIS�ڡ�����</a>";
$utf8_tag= "<a href=\"${SELF}?m=jtag&c=u\">UTF-8�ڡ�����</a>";

if ($code == "u") {
    $utf8_tag = "<strong>".strip_tags($utf8_tag)."</strong>";
} elseif ($code == "s") {
    $sjis_tag = "<strong>".strip_tags($sjis_tag)."</strong>";
} else {
    $euc_tag  = "<strong>".strip_tags($euc_tag)."</strong>";
}

echo <<<EOM

<div style="line-height:130%;margin:10px">
�ƥ����ȥܥå�����Υ�����ڡ�����Ž���դ���ȡ�<a href="#sample">�ڡ�������</a>�Τ褦�ʥ���ƥ�Ĥ��ڡ������ɽ������ޤ���<br />
�����ȤΥڡ�����֥����ףɣˣɡ��أϣϣУӤʤɤ�Ž���դ��뤳�Ȥ��Ǥ��ޤ���<br />
<span style="font-size:11px;color:gray">��Ž���դ����cgi��php��ư���ʤ��Ƥ�HTML��˵��Ҥ��������ư��ޤ�</span><br />
<!--Ž���դ�TAG-->
<textarea rows="10" cols="100" wrap="off">
<div align="center" style="font-size:12px">
<script language="javascript" src="${scripturi}?m=jscript&c=$code"></script>
<!--�ѹ��Բ�-->
<div style="text-align:center;font-size:10px;font-family:Tahoma">
<a href="http://rd.phpspot.org/?phpspot">PHP�Ǽ���</a>
</div>
<!--/�ѹ��Բ�-->
</div>
</textarea>
<!--/Ž���դ�TAG-->
<br />
$euc_tag <span style="color:#e0e0e0">|</span> $sjis_tag <span style="color:#e0e0e0">|</span> $utf8_tag<br />
Ž���դ���ڡ�����ʸ�������ɤˤ�äƻȤ�ʬ���Ƥ���������<br />
��ä�ʸ�������ɤΥ�����Ž���ʸ���������ޤ���ʬ����ʤ����ϣ����ि�ᤷ�ƤߤƤ���������
<hr>
<span style="color:red">��������ץ�̾/���ɽ�����ѹ�/����Ͻ���ޤ���<br />
��&nbsp;�����ʳ�����ʬ�Υե���ȤΥ�����/���ѹ��ϥ������륷�������Ǽ�ͳ�˹Ԥä�ĺ���ƹ����ޤ��󤬡�<br />
��&nbsp;�ѹ�������硢������ץ�̾/���ɽ����������褦Ŭ�ڤ˥�����/�������ԤäƤ���������</span><br />
��&nbsp;�������򤷤�������<a href="mailto:phpedit@hotmail.com">������</a>�ޤǤ��䤤��碌����������
</div>

<hr>
<a name="sample"></a>
�ʲ����֥���ץ�(�ǿ��ξ��󤫤���ɽ��)
<hr>
<div align="center" style="font-size:12px">
<div style="width:155px;background-color:#f0f0f0;padding:3px;border: 1pt solid #e0e0e0;">
<script language="javascript" src="${scripturi}?m=jscript"></script>
<!--�ѹ��Բ�-->
<div style="text-align:center;font-size:10px;font-family:Tahoma">
<a href="http://rd.phpspot.org/?phpspot">PHP�Ǽ���</a>
</div>
<!--/�ѹ��Բ�-->
</div>
</div>
EOM;

?>
<a href="javascript:history.go(-1)">��ɤ�</a>
</div>

<?php require(APP_TEMPLATE_DIR."parts/footer.php");?>