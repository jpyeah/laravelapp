<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Log;
use EasyWeChat\Message\Image;
use Illuminate\Support\Facades\DB; 
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;


class WechatController extends Controller
{
     public function serve(){
        
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function($message){
              switch ($message->MsgType) {
                case 'event':

                  switch ($message->Event) {
                          case 'subscribe'://weiguanzhu
                             $EventKey=str_replace('qrscene_', '', $message->EventKey);
                           //$EventKey=$message->EventKey;
                             $openId  =$message->FromUserName;
                              /* session(['openId'=>$openId]);
                             return "欢迎关注 overtrue！eventKey". $EventKey."op".$openId;
                            */
                             $this->saveopen($EventKey,$openId);
                             $media_id=$this->setcode($openId);
                             //return new Image(['media_id' => $media_id]);
                             return "欢迎关注川基车载";

                             break;
                          case 'SCAN'://guanzhu
                              $EventKey=$message->EventKey;
                              $openId  =$message->FromUserName;
                              
                              $this->saveopen($EventKey,$openId);
                            return "欢迎关注川基车载";
                            break;
                          default:
                            return "欢迎关注川基车载";
                             break;
                           }
                   
                    break;
                case 'text':
                   /* $qrcode = $wechat->qrcode;
              $result = $qrcode->forever(56);// 或者 $qrcode->forever("foo");
              $ticket = $result->ticket; // 或者 $result['ticket']
              $url = $result->url;
              */
                    return "欢迎关注川基车载";
                    //return $url;
                    break;
                case 'image':
                   /* $openId="okEwJs1EkAc_BvuvzuL7aP2u_dnA";
                    $media_id=$this->setcode($openId);
                    return new Image(['media_id' => $media_id]);
                    */
                    return "欢迎关注川基车载";
                    break;
                case 'voice':
                    return "图片";
                    break;
                case 'video':
                    # 视频消息...
                    break;
                case 'location':
                    return "位置";
                    break;
                case 'link':
                    # 链接消息...
                    break;
                // ... 其它消息
                default:
                    # code...
                    break;
                }
            
        });

        Log::info('return response.');

        return $wechat->server->serve();
    }
/*
    public function getauth(){
           if (Auth::check()) {
             return view('index');
           }else{
             	$wechat = app('wechat');
              $oauth = $wechat->oauth;
              return $oauth->redirect(); 
           }
    
    }

    public function auth_callback(){
           $wechat = app('wechat');
    	     $oauth =$wechat->oauth;
           // 获取 OAuth 授权结果用户信息
           $user = $oauth->user();
           
           $openId=$user->getId();

           session('openId',$openId);

           return redirect('home/login');
           
           //print_r($user);
    }
    */
    public function getauth(){
           if (Auth::check()) {
              return view('home/coupon');
           }else{
              $wechat = app('wechat');
              $oauth = $wechat->oauth;
              return $oauth->redirect(); 
           }
    
    }

    public function auth_callback(Request $request){

           $wechat = app('wechat');
           $oauth =$wechat->oauth;
           // 获取 OAuth 授权结果用户信息
           $user = $oauth->user();
           
           $openId=$user->getId();
          
           $user=DB::table("users")->where('wx_open_id','=',$openId)->get();
           $request->session()->put('openId',$openId);
           if($user){
             return redirect('home/oauthlogin')->with('openId',$openId);
           }else{
             return redirect('home/oauthregister')->with('openId',$openId);
           }          
           //print_r($user);
    }

    public function saveopen($EventKey,$openId){
           if($EventKey!=$openId){
           $user=DB::table("wxrelation")->where('user_open_id','=',$openId)->get();
           $parent=DB::table("wxrelation")->where(['parent_open_id'=>$openId,'user_open_id'=>$EventKey])->get();

           if($user){
             session('openId',$openId);
           }else{
          
          if(!$parent){
           	session('openId',$openId);
            $touser=DB::table("users")->where('wx_open_id','=',$openId)->get();

            $wechat = app('wechat');

           $userService =$wechat->user;
           // 获取 OAuth 授权结果用户信息
           $user = $userService->get($openId);
           $user_signature =$user->headimgurl;
           $user_name      =$user->nickname;

           $parent=$userService->get($EventKey);
           $parent_signature=$parent->headimgurl;
           $parent_name=$parent->nickname;
            
            if($touser){
                $user=DB::table("wxrelation")->insert(['user_name'=>$user_name,'parent_name'=>$parent_name,'user_open_id'=>$openId,'parent_open_id'=>$EventKey,'parent_id'=>$touser[0]->id]);
            }else{
                $user=DB::table("wxrelation")->insert(['user_name'=>$user_name,'parent_name'=>$parent_name,'user_open_id'=>$openId,'parent_open_id'=>$EventKey]);
            }

           }

           }

           }

    }

    public function getqrcode(){
    	   $wechat = app('wechat');
    	   //$wechat['config']->set('qrcode.action_info','223443534222');
    	   $qrcode =$wechat->qrcode;
           //$openId = session('openId');
    	   $str='okEwJs1EkAc_BvuvzuL7aP2u_dnA';
    	  // print_r(session('openId'));
    	   $result = $qrcode->forever($str);// 或者 $qrcode->forever("foo");
           $ticket = $result->ticket; // 或者 $result['ticket']
           $url = $result->url;
           return view('home/qrcode')->with('url',$url);
    }
    public function setmenu(){
    	    $buttons = [
			    [
			        "type" => "view",
			        "name" => "领取优惠劵",
			        "url"  => "http://test.bibicars.com/getauth"
			    ],
          [
              "type" => "view",
              "name" => "我的线下",
              "url"  => "http://test.bibicars.com/home/user"
          ],
          [
              "type" => "view",
              "name" => "我的二维码",
              "url"  => "http://test.bibicars.com/home/myshare"
          ],
			  /*  [
			        "name"       => "菜单",
			        "sub_button" => [
			            [
			                "type" => "view",
			                "name" => "下线用户",
			                "url"  => "http://test.bibicars.com/home/user"
			            ],
			            [
			                "type" => "view",
			                "name" => "领取优惠劵",
			                "url"  => "http://test.bibicars.com/home/coupon"
			            ],
			        ],
			    ],
          [
              "type" => "view",
              "name" => "川基车载",
              "url"  => "http://www.sztrykey.com/"
          ],
          */
			];
            $wechat = app('wechat');
            $menu = $wechat->menu;
            $menu->add($buttons);

    }
    public function setcode($openId){

    	   $wechat = app('wechat');
    	   //$wechat['config']->set('qrcode.action_info','223443534222');
    	   $qrcode =$wechat->qrcode;
           //$openId = session('openId');
    	   $str=$openId;
    	  // print_r(session('openId'));
    	   $result = $qrcode->forever($str);// 或者 $qrcode->forever("foo");
          // $ticket = $result->ticket; // 或者 $result['ticket']
           $url = $result->url;

           $QrCode = new BaconQrCodeGenerator;

           $path='qrcodes/'.$str.".png";
           $QrCode->format('png')->size(250)->color(255,0,255)->generate($url,public_path($path));

           $temporary = $wechat->material_temporary;
    	   $results = $temporary->uploadImage($path);
    	   return $results->media_id;
           
    }
    public function getqrinfo(){
    	    $wechat = app('wechat');
    	    $qrcode =$wechat->qrcode;
    }
    public function upload(){
    	   $wechat = app('wechat');
    	   $temporary = $wechat->material_temporary;
    	   $result = $temporary->uploadImage("1.pic.png");
    	   print_r($result->media_id);
    }

    public function getshucai(){
           $wechat = app('wechat');
           $material = $wechat->material;
           $lists = $material->lists('image', 0, 10);
           print_r($lists);

    }

}
