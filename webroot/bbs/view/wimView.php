<?php

class wimView extends View{
    function dispatch(&$context)
    {
        // 一般に許可されない場合
        if (!APP_WEBIMAGER_GUEST) {
            View_Manager::dispatch($context,"default");
            return;
        }
        $cgi = $context->getCgi();
        
        $host = $cgi->env("HTTP_HOST");
        $script=$cgi->env("SCRIPT_NAME");
        
        $scripturi  = "http://".$host.$script;
        
        $title = "WEBIMAGERの設定";
        
        require( $this->getTemplateName($context) );
    }
}

?>