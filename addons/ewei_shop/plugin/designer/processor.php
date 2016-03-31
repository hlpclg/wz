<?php
//捌零网络科技有限公司QQ2316571101
if (!defined('IN_IA')) {
    exit('Access Denied');
}
require IA_ROOT . '/addons/ewei_shop/defines.php';
require EWEI_SHOP_INC . 'plugin/plugin_processor.php';
class DesignerProcessor extends PluginProcessor
{
    public function __construct()
    {
        parent::__construct('designer');
    }
    public function respond($obj = null)
    {
        global $_W;
        $message = $obj->message;
        $content = $obj->message['content'];
        $msgtype = strtolower($message['msgtype']);
        $event   = strtolower($message['event']);
        if ($msgtype == 'text' || $event == 'click') {
            $page = pdo_fetch('select * from ' . tablename('ewei_shop_designer') . ' where keyword=:keyword and uniacid=:uniacid limit 1', array(
                ':uniacid' => $_W['uniacid'],
                ':keyword' => $content
            ));
            if (empty($page)) {
                return $this->responseEmpty();
            }
            $p       = htmlspecialchars_decode($page['pageinfo']);
            $p       = json_decode($p, true);
            $r_title = empty($p[0]['params']['title']) ? "未设置标题" : $p[0]['params']['title'];
            $r_desc  = empty($p[0]['params']['desc']) ? "未设置页面介绍" : $p[0]['params']['desc'];
            $r_img   = empty($p[0]['params']['img']) ? "" : $p[0]['params']['img'];
            $r_img   = set_medias($r_img);
            $news    = array(
                array(
                    'title' => $r_title,
                    'picurl' => $r_img,
                    'description' => $r_desc,
                    'url' => $_W['siteroot'] . 'app/index.php?i=' . $_W['uniacid'] . '&c=entry&m=ewei_shop&do=plugin&p=designer&pageid=' . $page['id']
                )
            );
            return $obj->respNews($news);
        }
        return $this->responseEmpty();
    }
    private function responseEmpty()
    {
        ob_clean();
        ob_start();
        echo '';
        ob_flush();
        ob_end_flush();
        exit(0);
    }
}