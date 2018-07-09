<?php
namespace Home\Controller;
use Think\Controller; 

use think\Request;
 
/**
 * 微信授权登录类
 * User: summer
 * Date: 2017/11/27
 * Time: 13:57
 */
class WeChatController extends Controller
{
   // private $appid =  C('WX_APPID');                 //微信公众号APPID
    //private $appsecret = C('WX_APPSECRET');             //密匙
  //  private $url = '';       //微信回调地址
 
 
    public function start($serviceUrl='')
    {
        $redirect = get_host_name().U('Home/WeChat/login',array('serviceUrl'=>$serviceUrl));
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . C('WX_APPID') . '&redirect_uri=' . urlencode($redirect) . '&response_type=code&scope=snsapi_userinfo&state=state#wechat_redirect';
 //var_dump(urlencode($redirect));die;
        header('location:' . $url);
    }
 
    /**
     * 登录
     */
    public function login($url='')
    {
        $code = $_GET['code'];
        $serviceUrl = $_GET['serviceUrl'];
        $access_token = $this->getUserAccessToken($code);
        $UserInfo = $this->getUserInfo($access_token);
        session('wx_userInfo',$UserInfo);
        redirect(urldecode($serviceUrl));    
    }
 
    /**
     * 获取授权token
     * @param $code
     * @return bool|string
     */
    private function getUserAccessToken($code)
    {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('WX_APPID')."&secret=".C('WX_APPSECRET')."&code=$code&grant_type=authorization_code";
 
        $res = file_get_contents($url);
        return json_decode($res);
    }
 
    /**
     * 获取用户信息
     * @param $accessToken
     * @return mixed
     */
    private function getUserInfo($accessToken)
    {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$accessToken->access_token&openid=$accessToken->openid&lang=zh_CN";
        $UserInfo = file_get_contents($url);
        return json_decode($UserInfo, true);
    }
 
    /**
     * 此AccessToken   与 getUserAccessToken不一样
     * 获得AccessToken
     * @return mixed
     */
    private function getAccessToken()
    {
        // 获取缓存
        $access = S('access_token');
        // 缓存不存在-重新创建
        if (empty($access)) {
            // 获取 access token
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".C('WX_APPID')."&secret=".C('WX_APPSECRET');
            $accessToken = file_get_contents($url);
 
            $accessToken = json_decode($accessToken);
            // 保存至缓存
            $access = $accessToken->access_token;
            S('access_token', $access, 7000);
        }
        return $access;
    }
 
 
    /**
     * 获取JS证明
     * @param $accessToken
     * @return mixed
     */
    private function _getJsapiTicket($accessToken)
    {
 
        // 获取缓存
        $ticket = S('jsapi_ticket');
        // 缓存不存在-重新创建
        if (empty($ticket)) {
            // 获取js_ticket
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $accessToken . "&type=jsapi";
            $jsTicket = file_get_contents($url);
            $jsTicket = json_decode($jsTicket);
            // 保存至缓存
            $ticket = $jsTicket->ticket;
            S('jsapi_ticket', $ticket, 7000);
        }
        return $ticket;
    }
 
    /**
     * 获取JS-SDK调用权限
     */
    public function shareAPi($url)
    {
        header("Access-Control-Allow-Origin:*");
        // 获取accesstoken
        $accessToken = $this->getAccessToken();
        // 获取jsapi_ticket
        $jsapiTicket = $this->_getJsapiTicket($accessToken);
        // -------- 生成签名 --------
        $wxConf = [
            'jsapi_ticket' => $jsapiTicket,
            'noncestr' => md5(time() . '!@#$%^&*()_+'),
            'timestamp' => time(),
            'url' => $url, //这个就是你要自定义分享页面的Url啦
        ];
        $string1 = "jsapi_ticket=".$wxConf['jsapi_ticket']."&noncestr=".$wxConf['noncestr']."&timestamp=".$wxConf['timestamp']."&url=".$wxConf['url'];
        // 计算签名
        $wxConf['signature'] = sha1($string1);
        $wxConf['appid'] = C('WX_APPID');
        return ($wxConf);
    }
    /**
     * 获取JS-SDK调用权限 返回ajax json
     */
    public function shareAPiAjax()
    {
      $url = I('get.url',sp_get_url());
        header("Access-Control-Allow-Origin:*");
        // 获取accesstoken
        $accessToken = $this->getAccessToken();
        // 获取jsapi_ticket
        $jsapiTicket = $this->_getJsapiTicket($accessToken);
        // -------- 生成签名 --------
        $wxConf = [
            'jsapi_ticket' => $jsapiTicket,
            'noncestr' => md5(time() . '!@#$%^&*()_+'),
            'timestamp' => time(),
            'url' => $url, //这个就是你要自定义分享页面的Url啦
        ];
        $string1 = "jsapi_ticket=".$wxConf['jsapi_ticket']."&noncestr=".$wxConf['noncestr']."&timestamp=".$wxConf['timestamp']."&url=".$wxConf['url'];
        // 计算签名
        $wxConf['signature'] = sha1($string1);
        $wxConf['appid'] = C('WX_APPID');
        echo json_encode($wxConf);
    }
}