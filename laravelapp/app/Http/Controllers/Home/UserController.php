<?php

namespace App\Http\Controllers\Home;

use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;
use DB;
use Redirect;
use Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;  
use App\Coupon;
class UserController extends Controller
{   
    /*
       public function __construct()
    {
        $this->middleware('auth');
    }
    */
       public function index()
    {  
    
        if(session('openId')){
        $users = DB::table('wxrelation')->where('parent_open_id',session('openId'))->paginate(5);
            return view('home.user',['users' => $users]);
        }else{
              $wechat = app('wechat');
              $wechat['config']->set('oauth.callback','/home/user_callback');
              $oauth = $wechat->oauth;
              return $oauth->redirect(); 
        }
   }
    public function user_callback(){
           $wechat = app('wechat');
           $oauth =$wechat->oauth;
           // 获取 OAuth 授权结果用户信息
           $user = $oauth->user();
           $openId=$user->getId();
           session('openId',$openId);
           $users = DB::table('wxrelation')->where('parent_open_id',$openId)->paginate(5);
   
        return view('home.user',['users' => $users]);
    }
        


    public function coupon(Request $request){
        if(Auth::check()){
        $user_id=Auth::user()->id;
        $coupon=DB::table("coupon")->where('user_id','=',$user_id)->get();
        
        if(!$coupon){

          if ($request->isMethod('post')) {
            $validator = $this->validateCoupon($request->input());
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $coupon = new Coupon();
            $coupon->address = $request->address;
            $coupon->user_id = Auth::user()->id;
            $coupon->is_get =1;
            $coupon->created = time();
            if($coupon->save()){
                return redirect('home')->with('success', '注册成功！');
            }else{
                return back()->with('error', '注册失败！')->withInput();
            }
         }

        }
        

        return view('home.coupon',['coupon' => $coupon]);
        //return view('home.coupon');
      }else{
        return Redirect::to('/getauth');
      }
    }

    protected function validateCoupon(array $data)
    {
        return Validator::make($data, [
            'address' => 'required',
        ], [
            'required' => ':attribute 为必填项',
        ], [
            'address' => '地址',
        ]);
    }

    public function test()
    {
        $users = DB::table('wxrelation')->leftJoin('users', 'wxrelation.user_id', '=', 'users.id')->where('parent_id',Auth::user()->id)->select('user_id','phone','name')->get();
        $arr=array();
        foreach($users as $k =>$val){
                $arr[$k]->name=$val['name'];
                $arr[$k]->user_id=$val['user_id'];
                $arr[$k]->phone=$val['phone'];
        }
        $users=$arr;
        var_dump($users);
        $perPage = 3;
	    if ($request->has('page')) {
	            $current_page = $request->input('page');
	            $current_page = $current_page <= 0 ? 1 :$current_page;
	    } else {
	            $current_page = 1;
	    }

	    $item = array_slice($users, ($current_page-1)*$perPage, $perPage); //注释1
	    $total = count($users);

	    $paginator =new LengthAwarePaginator($item, $total, $perPage, $currentPage, [
	            'path' => Paginator::resolveCurrentPath(),  //注释2
	            'pageName' => 'page',
	    ]);

	    $userlist = $paginator->toArray()['data'];
        
	    return view('home.user', compact('userlist', 'paginator'));
    }

     public function object_array($array) {  
	    if(is_object($array)) {  
	        $array = (array)$array;  
	     } if(is_array($array)) {  
	         foreach($array as $key=>$value) {  
	             $array[$key] = $this->object_array($value);  
	             }  
	     }  
	     return $array;  
     } 


  
}
