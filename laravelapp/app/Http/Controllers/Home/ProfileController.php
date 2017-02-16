<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class ProfileController extends Controller
{   
	   public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){

       $wechat = app('wechat');
	   //$wechat['config']->set('qrcode.action_info','223443534222');
	   $qrcode =$wechat->qrcode;
       //$openId = session('openId');
       $openId=Auth::user()->wx_open_id;
       if($openId){
       	  $str=$openId;
       }else{
          $wechat = app('wechat');
          $oauth = $wechat->oauth;
          return $oauth->redirect(); 
       	  //$str='1233445';
       }
	   
	    // print_r(session('openId'));
	    $result = $qrcode->forever($str);// 或者 $qrcode->forever("foo");
       $ticket = $result->ticket; // 或者 $result['ticket']
       $url = $result->url;
       return view('home.profile')->with('url',$url);
        
    }

    public function test(){
    	   $openId="okEwJs3gNOLyS6-5p7nS3k6gzyDM";
    	   $wechat = app('wechat');

           $userService =$wechat->user;
           // 获取 OAuth 授权结果用户信息
           $user = $userService->get($openId);
           $signature =$user->headimgurl;
           $name      =$user->nickname;
           print_r($user);
    }

    public function share(){
      return view('home.share');
    }
}
