<?php

class resAction extends Action{
    function dispatch(&$context)
    {
        $cgi = $context->getCgi();
        $script = $cgi->post("script");
        $script = stripcslashes($script);
        
        if (APP_USE_CAPTCHA == 1) {
            $captcha_string = $cgi->post("captcha");
            if ($captcha_string != $_SESSION["captcha_keystring"]) {
                trigger_error("����ǧ�ڤǤ������ͤ��ְ�äƤ��ޤ���");
            }
        }
        
        $data = new Data_Model_Res($cgi);
        
        if ($err = $data->check()) {
            trigger_error($err);
        } else {
            // ���å�����̾����¸
            $cgi->cookie->set("cname",$data->get("name"));
            
            $id = $cgi->post("id");
            
            $ctrl = new Ctrl_File_Data("Data_Model_User",APP_DATA_FILE);
            
            $query = new Data_Query();
            $query->set("id", $id);
            
            $obj = $ctrl->find($query);
            
            if (empty($obj)) { 
                trigger_error("�Ƶ��������Ĥ���ޤ���");
            }
            
            if (APP_KIJI_UP) {
                // �ޥ����ե��������¸(��������־�ˤ�����)
                $ctrl->delete($obj);
                $ctrl->insert($obj,"first");
            }
            
            // �쥹�ե��������¸
            $file = new File_Data(APP_RES_DIR.$id.".cgi");
            
            $file->writeFirst($data->encData());
            
            /*
            if (count($file->get()) > APP_DATA_SAVE_MAX) {
                $newar = $file->slice(0,APP_DATA_SAVE_MAX);
                $file->overwrite($newar);
            }
            */
            
            // ���ڡ�������å������
            Cache_Writer::execute($context);
            
            // ���̥ڡ�������å������
            $context->set("one_id",$data->get("id"));
            Cache_Writer::execute($context, "one");
            
            return "write";
        }
    }
}

?>