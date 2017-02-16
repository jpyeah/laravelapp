<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;

class ShareController extends Controller
{   

    public function share($id){

         if($id){
               session('fromid',$id);
          }else{
              $id=session('fromid');
         }
         if(session('openId')){
          
           return redirect('/home/share_callback/'.$id);
         }else{
            $wechat = app('wechat');
            $wechat['config']->set('oauth.callback','/home/share_callback/'.$id);
            $oauth = $wechat->oauth;
            return $oauth->redirect(); 
         }
        /*
         if(Auth::check()){
             
             $openId=Auth::user()->wx_open_id;
             session('openId',$openId);
         }
         
         if(!session('openId')){
          */

       /*  }else{ 

           $openId=session('openId');
           $wechat = app('wechat');

           $userService =$wechat->user;
           // 获取 OAuth 授权结果用户信息
           $user = $userService->get($openId);
           $signature =$user->headimgurl;
           $name      =$user->nickname;
           //$wechat['config']->set('qrcode.action_info','223443534222');
           $qrcode =$wechat->qrcode;
           //$openId = session('openId');
           $str=$openId;
            // print_r(session('openId'));
           $result = $qrcode->forever($str);// 或者 $qrcode->forever("foo");
          // $ticket = $result->ticket; // 或者 $result['ticket']
           $url = $result->url;
           
           $QrCode = new BaconQrCodeGenerator;
           $path='/qrcodes/'.md5($str).".png";
           
           $QrCode->format('png')->size(250)->color(255,0,255)->merge('/public/1.pic.png',.15)->generate($url,public_path($path));
           return view('home/share',['url'=>$path,'signature'=>$signature,'name'=>$name]);
         }
         */

    }
    public function share_callback($id){
          
          
           if(session('openId')){
               $openId=session('openId');
               $wechat = app('wechat');
               $userService =$wechat->user;
               // 获取 OAuth 授权结果用户信息
               $user = $userService->get($openId);
               $signature =$user->headimgurl;
               $name      =$user->nickname;
               
           }else{
               $wechat = app('wechat');
               $oauth =$wechat->oauth;
               // 获取 OAuth 授权结果用户信息
               $user = $oauth->user();
               $openId=$user->getId();
               $name      =$user->getNickname(); // 对应微信的 nickname
               $signature =$user->getAvatar(); // 头像网址
           }
           
           if($id != $openId){
               $wechat = app('wechat');
               $userService =$wechat->user;
               // 获取 OAuth 授权结果用户信息
               $user = $userService->get($id);
               $signature =$user->headimgurl;
               $name      =$user->nickname;
           }

           session(['openId' =>$openId]);
           //$wechat['config']->set('qrcode.action_info','223443534222');
           $qrcode =$wechat->qrcode;
           //$openId = session('openId');
           $str=$id;
           // print_r(session('openId'));
           $result = $qrcode->forever($str);// 或者 $qrcode->forever("foo");
           //$ticket = $result->ticket; // 或者 $result['ticket']
           $url = $result->url;

           $QrCode = new BaconQrCodeGenerator;

           $path='/qrcodes/'.md5($str).".png";
           $QrCode->format('png')->size(250)->color(255,0,255)->merge('/public/bc.png',.15)->generate($url,public_path($path));
           return view('home/share',['url'=>$path,'signature'=>$signature,'name'=>$name,'Id'=>$openId]);
           
    }

    public function myshare(){
            $wechat = app('wechat');
            $wechat['config']->set('oauth.callback','/home/myshare_callback');
            $oauth = $wechat->oauth;
            return $oauth->redirect(); 
    }

    public function myshare_callback(){

           $wechat = app('wechat');
           $oauth =$wechat->oauth;
           // 获取 OAuth 授权结果用户信息
           $user = $oauth->user();
           $openId=$user->getId();
           $name      =$user->getNickname(); // 对应微信的 nickname
           $signature =$user->getAvatar(); // 头像网址

           $qrcode =$wechat->qrcode;
           //$openId = session('openId');
           $str= $openId;
           // print_r(session('openId'));
           $result = $qrcode->forever($str);// 或者 $qrcode->forever("foo");
           //$ticket = $result->ticket; // 或者 $result['ticket']
           $url = $result->url;

           $QrCode = new BaconQrCodeGenerator;

           $path='/qrcodes/'.md5($str).".png";
           $QrCode->format('png')->size(250)->color(255,0,255)->merge('/public/bc.png',.15)->generate($url,public_path($path));
           return view('home/share',['url'=>$path,'signature'=>$signature,'name'=>$name,'Id'=>$openId]);
           
    }
}
