<br />
<div class="waku">
<center>
<div style="line-height:140%">
<?php

echo "��".$allcount."�� ";

$app = APP_FILENAME;
$pref= APP_PAGE_PREFIX;

$key = $this->get("key");

if ($prevsign) {
    echo "...";
}

foreach ($pageindex as $num => $index) {
    if($index == $current){
        echo <<<EOM
($num) 
EOM;
    }elseif($index == 0){
        echo <<<EOM
<a href="$app">($num)</a> 
EOM;
    }else{
        echo <<<EOM
<a href="$pref${index}.html">($num)</a> 
EOM;
    }
}

if ($nextsign) {
    echo "...";
}

?>
<br>
<?php

if (!$this->isFirstPage()){
    if ($prevpage == "0") {
        echo <<<EOM
<a href="$app">����</a>
EOM;
    } else {
        echo <<<EOM
<a href="$pref${prevpage}.html">����</a>
EOM;
    }
    if (!$this->isLastPage()) {
        echo " ";
    }
}

if (!$this->isLastPage()){
    echo <<<EOM
<a href="$pref${nextpage}.html">����</a>
EOM;
}

?>
</div>
</center>
</div>