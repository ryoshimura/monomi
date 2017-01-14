<?php

class writeAction extends Action{
    function dispatch(&$context)
    {
        $cgi = $context->getCgi();
        $script = $cgi->post("script");
        $script = stripcslashes($script);
        
        if (APP_USE_CAPTCHA == 1) {
            $captcha_string = $cgi->post("captcha");
            if ($captcha_string != $_SESSION["captcha_keystring"]) {
                trigger_error("画像認証での入力値が間違っています。");
            }
        }
        
        $data = new Data_Model_User($cgi);
        
        if ($err = $data->check()) {
            trigger_error($err);
        } else {
            // クッキーに名前保存
            $cgi->cookie->set("cname", $data->get("name"));
            
            $data->saveFile("file",APP_DATA_DIR.$data->get("id"));
            
            // マスタファイルに保存
            $file = new File_Data(APP_DATA_FILE);
            $file->writeFirst($data->encData());
            
            if (count($file->get()) > APP_DATA_SAVE_MAX) {
                $newar = $file->slice(0,APP_DATA_SAVE_MAX);
                $file->overwrite($newar);
            }
            
            // 全ページキャッシュ作成
            Cache_Writer::execute($context);
            // 個別ページキャッシュ作成
            $context->set("one_id",$data->get("id"));
            Cache_Writer::execute($context, "one");
        }
    }
}

?>