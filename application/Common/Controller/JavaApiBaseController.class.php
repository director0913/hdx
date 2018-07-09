<?php
namespace Common\Controller;
use Common\Controller\AppframeController;
class JavaApiBaseController extends AppframeController {
	
	/**
     * 封装发送数据
     * @param array $data 发送的数据
     * verify 0 数据code 不验证，1 验证
     * @param int $code 0 不打印 1 打印 2 打印并退出
     */
    public function send($data, $code = 0)
    {
        // 数据处理
        $data['url'] = $this->url($data['url']); // url
        // header 头信息
        $data['header'] = [
            "Content-Type: application/json",
            "appKey:".C('APP_ID')
        ];

        // 判断 data 是否设置,不全参数
        if (!isset($data['data'])) {
            $data['data'] = [];
        }

        // 整理发送数据 除 get 外并且有数据 全部转json
        if ($data['method'] == 'get' && $data['data']) {
            $url = '?' . http_build_query($data['data']); // 整理 get 参数
            $data['url'] = rtrim($data['url'], '?') . $url;
            unset($data['data']); // 删除 data 数据
        } else if ($data['method'] != 'get' && $data['data']) {
            $data['data'] = tojson($data['data']);
        }

        // 数据验证
        if (isset($data['verify']) && $data['verify'] == 1) {
            $verify = 1; // 不验证数据
        } else {
            $verify = 0; // 验证数据（默认）
        }

        // 发送数据
        $data = send($data, $code);

        // 数据解析与验证
        return $this->verify($data, $verify);
    }

    /* 
    *   java 数据解析与验证 
    * @param array $data java 返回的数据
    */
    public function verify($data, $verify)
    {
        // 数据为空
        if (empty($data)) {
//            abort(404, '外部接口请求错误');
            echo "外部接口请求错误";
        }

        // 解析json 
        $json = json_decode($data);
        $json_last_error = json_last_error(); // json 错误码

        // 数据解析失败或者错误
        if ($json_last_error != 0) {
//            abort(404, '外部接口请求错误');
            echo "外部接口请求错误";
        }

        // code 未设置，防止意外错误
        if (!isset($json->code)) {
//            abort(404, '外部接口请求错误');
            echo "外部接口请求错误";
        }

        // 不验证数据,返回全部数据
        if ($verify == 1) {
            return $json;
        }

        // code 不为 0或3 错误码
        if ($json->code != 0) {
            header("Content-Type: application/json"); // 加入json 头
            echo $data;
            exit;
        }

        // 返回数据
        return $json->result;
    }

    /*
    * java 接口地址
    * @param string $url java 请求地址
    */
    public function url($uri)
    {
        $url = C('JAVA');

        return rtrim($url, '/') . $uri; // 合并为完整url
    }
	
}