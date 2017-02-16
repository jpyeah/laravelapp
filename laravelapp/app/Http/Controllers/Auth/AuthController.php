<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Validator;
use App\Http\Requests;
use Auth;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\DB; 

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    //登录页面
    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = $this->validateLogin($request->input());
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            if (Auth::guard('web')->attempt($this->validateUser($request->input()))) {
                $request->session()->put('phone', $request->phone);

                if(session('openId') && !Auth::user()->wx_open_id){
                    $user = new User();
                    $user::where('phone', '=',Auth::user()->phone)->update(array('wx_open_id' => session('openId')));
                    return Redirect::to('home')->with('success', '登录成功！')->with('openId',session('openId'));
                }
                return Redirect::to('home')->with('success', '登录成功！');
            }else {
                return back()->with('error', '账号或密码错误')->withInput();
            }
        }
        return view('auth.login');
    }
    //登录页面验证
    protected function validateLogin(array $data)
    {
        return Validator::make($data, [
            'phone' => 'required',
            'password' => 'required',
        ], [
            'required' => ':attribute 为必填项',
            'min' => ':attribute 长度不符合要求'
        ], [
            'phone' => '邮箱',
            'password' => '密码'
        ]);
    }
    //验证用户字段
    protected function validateUser(array $data)
    {
        return [
            'phone' => $data['phone'],
            'password' => $data['password']
        ];
    }
    //退出登录
    public function logout()
    {
        if(Auth::guard('web')->user()){
            Auth::guard('web')->logout();
        }
        return Redirect::to('home');
    }
    //注册
    public function register(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = $this->validateRegister($request->input());
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $user = new User();
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->company = $request->company;
            $user->password = bcrypt($request->password);
            if(session('openId')){
                $user->wx_open_id=session('openId');
            }

            if($user->save()){
                return redirect('home/login')->with('success', '注册成功！');
            }else{
                return back()->with('error', '注册失败！')->withInput();
            }
        }
        return view('auth.register');
    }


    public function oauthregister(Request $request){
        $openId=$request->session()->get('openId');
        if ($request->isMethod('post')) {
            $validator = $this->validatewxRegister($request->input());
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $user = new User();
            $user->name = $request->name;
            $user->phone = $request->phone;
            $user->company = $request->company;
            $user->company_type = $request->company_type;
            $user->password = bcrypt(123456);
            $user->wx_open_id = $request->wx_open_id;

            if($user->save()){
                $user=DB::table("users")->where('wx_open_id','=',$request->wx_open_id)->get();
                $user_id=$user[0]->id;
                $request->session()->put('phone',$request->phone);
                $request->session()->put('openId',$request->wx_open_id);
                Auth::loginUsingId($user_id);
                return redirect('home')->with('success', '注册成功！');
            }else{
                return back()->with('error', '注册失败！')->withInput();
            }
        }
        return view('home.register')->with('openId',$openId);
    }

    public function oauthlogin(Request $request){
       $openId=$request->session()->get('openId');
       $user=DB::table("users")->where('wx_open_id','=',$openId)->get();
       $user_id=$user[0]->id;
       $phone=$user[0]->phone;
       $request->session()->put('phone',$phone);
       $request->session()->put('openId',$openId);
       $updated=DB::table("wxrelation")->where('user_open_id','=',$openId)->update(array('user_id' => $user_id));
       Auth::loginUsingId($user_id);
       return Redirect::to('home/coupon')->with('success', '登录成功！');

       //return Redirect::to('home')->with('success', '登录成功！');
    }
    
        protected function validatewxRegister(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|alpha_num|max:255',
            'phone' => 'required|regex:/^1[34578][0-9]{9}$/|unique:users',
            'company' => 'required|max:255',
            'company_type' => 'required|max:255',
            //'password' => 'required|min:6|confirmed',
           // 'password_confirmation' => 'required|min:6|',
            'wx_open_id' => 'required|unique:users'
        ], [
            'required' => ':attribute 为必填项',
            'min' => ':attribute 长度不符合要求',
            'confirmed' => '两次输入的密码不一致',
            'unique' => '该phone已经被人占用',
            'alpha_num' => ':attribute 必须为字母或数字',
        ], [
            'name' => '昵称',
            'phone' => 'mobbile',
            'password' => '密码',
            'password_confirmation' => '确认密码'
        ]);
    }


    protected function validateRegister(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|alpha_num|max:255',
            'phone' => 'required|regex:/^1[34578][0-9]{9}$/|unique:users',
            'company' => 'required|max:255',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6|'
        ], [
            'required' => ':attribute 为必填项',
            'min' => ':attribute 长度不符合要求',
            'confirmed' => '两次输入的密码不一致',
            'unique' => '该phone已经被人占用',
            'alpha_num' => ':attribute 必须为字母或数字'
        ], [
            'name' => '昵称',
            'phone' => 'mobbile',
            'password' => '密码',
            'password_confirmation' => '确认密码'
        ]);
    }

}