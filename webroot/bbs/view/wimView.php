<?php

class wimView extends View{
    function dispatch(&$context)
    {
        // ���̤˵��Ĥ���ʤ����
        if (!APP_WEBIMAGER_GUEST) {
            View_Manager::dispatch($context,"default");
            return;
        }
        $cgi = $context->getCgi();
        
        $host = $cgi->env("HTTP_HOST");
        $script=$cgi->env("SCRIPT_NAME");
        
        $scripturi  = "http://".$host.$script;
        
        $title = "WEBIMAGER������";
        
        require( $this->getTemplateName($context) );
    }
}

?>