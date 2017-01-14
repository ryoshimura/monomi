<?php require(APP_TEMPLATE_DIR."parts/header.php");?>
<?php

$SELF = APP_FILENAME;
$SELF_DIR = dirname(APP_FILENAME)."/";

?>
<h1><?php echo APP_TITLE; ?></h1>
<div class="waku">
<table width="95%">
<form method="post" action="<?php echo $SELF; ?>?<?php echo uniqid(""); ?>" enctype="multipart/form-data">
<input type="hidden" name="m" value="write">
<tr>
<td width=1% nowrap align=right valign=top>̾�� : </td>
<td width=99%><input type="text" size="20" name="name" value="<?php echo $cname; ?>"></td>
</tr>
<tr>
<td width=1% nowrap align=right valign=top>�����ȥ� : </td>
<td width=99%><input type="text" size="80" name="title"></td>
</tr>
<tr>
<td width=1% nowrap align=right valign=top>��å����� :</td>
<td width=99%><textarea name="message" rows="5" cols="80"></textarea></td>
</tr>
<tr>
<td width=1% nowrap align=right valign=top>������� : </td>
<td width=99%><input type="password" size="9" name="delkey"></td>
</tr>
<?php if (APP_USE_CAPTCHA == 1) { ?>
<tr>
<td width=1% nowrap align=right valign=top>����ǧ�� : </td>
<td width=99% style="line-height:150%">���ϥܥå����˲�����Ʊ��ʸ�������Ϥ��Ƥ�������<br>
<img src="<?php echo $SELF_DIR.APP_CAPTCHA_DIR; ?>index.php?<?php echo session_name()?>=<?php echo session_id()?>" align="absmiddle">
 �� <input type="text" size="10" name="captcha">
</td>
</tr>
<?php } ?>
<tr>
<td width=1% nowrap></td>
<td width=99%><input type="submit" value="������ơ���">
</td>
</tr>
</form>
</table>
</div>
<div class="waku_i">
<a href="<?php echo APP_FILENAME.""; ?>">�����</a>
<?php if (APP_MAIL_POST) { ?>
 | 
<a href="<?php echo APP_FILENAME."?m=pop3"; ?>">�᡼����ƥ����å�</a>
<?php } ?>
</div>
<?php

if ($cachemode == "yes") {
    $IPATH = "../";
    $HPATH = "";
} else {
    $IPATH = "";
    $HPATH = "html/";
}

foreach ($objs as $obj) {
    $id = $obj->get("id");
    $name = $obj->get("name");
    $mail = $obj->get("mail");
    $title= $obj->get("title");
    $mess = $obj->get("message");
    $url  = $obj->get("url");
    $date = date("Y/m/d H:i:s",$obj->get("date"));
    if (!file_exists($file)){ $file = ""; }
    
    if ($mail != "") { $name = "<a href=mailto:$mail>$name</a>"; }
    if ($url  != "") { $url  = "<a href=$url>$url</a>"; }
    
    if ($title!= "") { $title = "$title - "; }
    if ($mess != "") { $mess = $mess."<br>"; }
    
    echo <<<EOM
<div class="waku_i">
<table width=95%>
<tr>
<td width=100% valign=top>
<div style="margin:8px;line-height:140%">
<strong>$title</strong><strong>$name</strong> <span style="font-size:10px;font-family:Tahoma">[$date]</span> [ <a href="$HPATH${id}.html">1��ɽ��</a> ] [ <a href="${SELF}?m=delete&id=$id">���</a> ]<br />
$mess<br>
<hr>
EOM;
    // �����쥹��ɽ��
    $resobjs = Article_Res::getObjects(APP_RES_DIR.$id.".cgi");
    
    foreach ($resobjs as $resobj) {
        $res_name = $resobj->get("name");
        $res_message = $resobj->get("message");
        $res_date = date("Y/m/d H:i:s",$resobj->get("date"));
        echo <<<EOM
$res_name <span style="font-size:10px;font-family:Tahoma">[$res_date]</span><br />
<div class="res">
$res_message
</div>
EOM;
    }
    
    if (count($resobjs) > 0) {
        echo "<hr>";
    }
    
    echo <<<EOM
<form method="post" action="$SELF" style="margin:0px;padding:0px;">
<input type="hidden" name="m" value="res">
<input type="hidden" name="id" value="$id">
EOM;

    if (APP_USE_CAPTCHA == 1) {
        echo '<div style="margin-bottom:6px;">';
        echo '����ǧ��: <img src="'.$SELF_DIR.APP_CAPTCHA_DIR.'index.php?'.session_name().'='.session_id().'" align="absmiddle">';
        echo ' �� <input type="text" size="10" name="captcha">���ֿ������硢���ϥܥå����˲�����Ʊ��ʸ�������Ϥ��Ƥ�������<br>';
        echo '</div>';
    }

    echo <<<EOM
̾��: <input type="text" name="name" size="10" value="$cname">
��å�����: <textarea name="message" rows="1" cols="60"></textarea> <input type="submit" value="�ֿ�"><br>
</form>
</div>
</td>
</tr>
</table>
</div>
EOM;
}

?>

<?php

// �ڡ�������ǥå����ν���
if ($cachemode != "yes") {
    $pageindex->draw(APP_TEMPLATE_DIR."parts/page_index.php");
} else {
    $pageindex->draw(APP_TEMPLATE_DIR."parts/page_index2.php");
}

?>

<?php require(APP_TEMPLATE_DIR."parts/footer.php");?>