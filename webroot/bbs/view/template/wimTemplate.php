<?php require(APP_TEMPLATE_DIR."parts/header.php");?>

<div class="waku">
<span style="font-size:14px;font-weight:bold">WEBIMAGER</span><hr />
���ηǼ��Ĥˤ� <a href="http://rd.phpspot.org/?phpspot">php-spot</a> �����ۤ��Ƥ��� <a href="http://fol.axisz.jp/php/pgwim.html">WEBIMAGER</a> �ˤ����Ƥ���ǽ�Ǥ���<br>
<a href="http://fol.axisz.jp/php/pgwim.html">WEBIMAGER</a> ��Ȥ��Хѥ������Τ���Ȥ�����ե�������ñ���Ĥ���ڤ˥��åץ��ɤǤ��ޤ���<br><br>

<a href="http://fol.axisz.jp/php/pgwim.html">WEBIMAGER</a> �Ѥ�����ϰʲ��ˤʤ�ޤ���<br>
<br>
<div class="waku">
<table width=95% cellpadding=5>
<tr>
<td width=1% nowrap align=right>������գң�:</td><td><b><?php echo $scripturi; ?></b></td>
</tr><tr>
<td width=1% nowrap align=right>�ɣ�:</td><td><?php echo APP_WEBIMAGER_USER; ?></td>
</tr><tr>
<td width=1% nowrap align=right>�У��ӣ�:</td><td><?php echo APP_WEBIMAGER_PASS; ?></td>
</tr>
</table>
</div>
<br>
WEBIMAGER ������������Ǿ嵭���Ƥ����ꤹ��Ф��ηǼ��Ĥ˥��åץ��ɤ��뤳�Ȥ��Ǥ���褦�ˤʤ�ޤ���

<hr>
<?php

echo "<a href=\"".APP_FILENAME."?".md5(uniqid(""))."\">�Ǽ��Ĥˤ�ɤ�</a>";

?>
</div>

<?php require(APP_TEMPLATE_DIR."parts/footer.php");?>