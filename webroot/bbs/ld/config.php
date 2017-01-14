<?php

/**--------------------
 * �ǥ����������
 */

/**
 * ���Τ��طʿ�
 */
define("APP_HTML_BGCOLOR","#f2f2f2");

/**
 * �Ȥ��طʿ�
 */
define("APP_HTML_WAKUBGCOLOR","white");

/**
 * �ǥ�������ꥫ�����ޥ������������� view/template/ �ǥ��쥯�ȥ�ˤ���ե�������Խ����ޤ�
 * CSS���ҥե������ view/template/parts/header.php �Ǥ�
 */

// -----------------------

/**
 * �����ǡ�����¸�ǥ��쥯�ȥ�
 */
//define("APP_DATA_DIR","data/");
define("APP_DATA_DIR","/bbs/data/");

/**
 * �ֿ������ǥ��쥯�ȥ�
 */
define("APP_RES_DIR","/bbs/res/");

/**
 * �����ǡ����ե�����̾
 */
define("APP_DATA_FILE",APP_DATA_DIR."data.cgi");

/**
 * �ǡ���ɽ�����
 */
define("APP_DATA_VIEW_COUNT",10);

/**
 * �ǡ�����¸������
 */
define("APP_DATA_SAVE_MAX",500);

/**
 * rss���ϥǡ������
 */
define("APP_RSS_VIEW_COUNT",10);

/**
 * javascript���ϥǡ������
 */
define("APP_JS_VIEW_COUNT",10);

/**
 * �����ֿ����˵����գ�(1:�����գФ���,0:�����գФ��ʤ�)
 */
define("APP_KIJI_UP",1);

/**
 * title������˵��Ҥ��륿���ȥ�
 */
define("APP_TITLE", "物見.info暫定BBS");

/**
 * �ǡ������������ - ǧ�ڥ桼��
 * ! ɬ���ѹ����Ƥ�������
 */
$_APP_AUTH_USER = array(
"user"=>"pass"
);

/**--------------------
 * CAPTCHA������
 */
define("APP_CAPTCHA_DIR", "/bbs/kcaptcha_lib/"); // ���ΤޤޤǤ��ɤ�
define("APP_USE_CAPTCHA", 0); // 0:�Ȥ�ʤ�, 1:�Ȥ�

/**--------------------------------
 * �᡼����Ƶ�ǽ����Ѥ���(0:�Ȥ�ʤ�;1:�Ȥ�)
 */
define("APP_MAIL_POST",1);

/**
 * �᡼��������ѤκݤΥ᡼�륵����̾
 */
define("APP_MAIL_HOST", "mail.server.jp");

/**
 * �᡼��������ѤκݤΥ桼���ɣ�
 */
define("APP_MAIL_UID", "username");

/**
 * �᡼��������ѤκݤΥѥ����
 */
define("APP_MAIL_PASS","pass");

/**
 * �᡼������ѤΥ᡼�륢�ɥ쥹
 */
define("APP_MAIL_ADDR", "post@mail.server.jp");

// -----------------------------------

/** ---------------------
 * ����å���ڡ�����Ƭ��
 */
define("APP_PAGE_PREFIX","im");

// ���ꤳ���ޤ� -----------------------------------

if (APP_USE_CAPTCHA == 1) {
    session_start();
}

?>