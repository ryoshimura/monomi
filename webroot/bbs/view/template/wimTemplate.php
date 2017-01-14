<?php require(APP_TEMPLATE_DIR."parts/header.php");?>

<div class="waku">
<span style="font-size:14px;font-weight:bold">WEBIMAGER</span><hr />
この掲示板には <a href="http://rd.phpspot.org/?phpspot">php-spot</a> で配布している <a href="http://fol.axisz.jp/php/pgwim.html">WEBIMAGER</a> による投稿が可能です。<br>
<a href="http://fol.axisz.jp/php/pgwim.html">WEBIMAGER</a> を使えばパソコン上のありとあらゆるファイルを簡単かつお手軽にアップロードできます。<br><br>

<a href="http://fol.axisz.jp/php/pgwim.html">WEBIMAGER</a> 用の設定は以下になります。<br>
<br>
<div class="waku">
<table width=95% cellpadding=5>
<tr>
<td width=1% nowrap align=right>送信先ＵＲＬ:</td><td><b><?php echo $scripturi; ?></b></td>
</tr><tr>
<td width=1% nowrap align=right>ＩＤ:</td><td><?php echo APP_WEBIMAGER_USER; ?></td>
</tr><tr>
<td width=1% nowrap align=right>ＰＡＳＳ:</td><td><?php echo APP_WEBIMAGER_PASS; ?></td>
</tr>
</table>
</div>
<br>
WEBIMAGER の送信先設定で上記内容を設定すればこの掲示板にアップロードすることができるようになります。

<hr>
<?php

echo "<a href=\"".APP_FILENAME."?".md5(uniqid(""))."\">掲示板にもどる</a>";

?>
</div>

<?php require(APP_TEMPLATE_DIR."parts/footer.php");?>