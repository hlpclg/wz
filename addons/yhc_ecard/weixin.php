
<?php
#ini_set("display_errors", "On");
#error_reporting(E_ALL);

class Weixin {
    private static function downloadWeixinFile($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);    
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //只取body头
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $imageAll = array_merge(array('header' => $httpinfo), array('body' => $package)); 
        return $imageAll;
    }

    public static function downloadImage($mediaid, $filename) {
        //下载图片  
        global $_W;
        $uniacid = !empty($_W['uniacid']) ? $_W['uniacid'] : $_W['acid'];       
        load()->func('file');
        $access_token = $_W['account']['access_token']['token'];
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=$access_token&media_id=$mediaid";
        $fileInfo = self::downloadWeixinFile($url);
        $filedir = '/images/'.$uniacid.'/'.date("Y").'/'.date("m")."/";
        $updir = ATTACHMENT_ROOT.$filedir;      
        if(!is_dir($updir)){ 
            mkdirs($updir); 
        }  
        $filename = $filename.".jpg";
        self::saveWeixinFile($updir.$filename, $fileInfo["body"]);
        return $filedir.$filename;
    }

    private static function saveWeixinFile($filename, $filecontent) {      
        $local_file = fopen($filename, 'w');
        if (false !== $local_file){
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);
            }
        }
    }
}
?>