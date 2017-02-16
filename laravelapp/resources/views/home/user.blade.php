@extends('layouts.app')

@section('content')
<div class="container">
    @if ($users)
     <table class="table table-bordered">
     <tr>
         <th>用户名</th>
         <th>添加时间</th>
         <th>是否会员</th>
     </tr>
     @foreach ($users as $user)
     <tr>
     	<td>{{$user->user_name}}</td>
        <td>{{$user->created_at}}</td>
        @if ( $user->user_id )
            <?php echo "<td>是</td>" ?>
        @else
           <?php echo "<td>否</td>" ?>
        @endif 
     </tr>
     @endforeach
    </table>
    {!! $users->render() !!}
   @else
       <table class="table table-bordered">
        <tr><p>no bady here</p></tr>
       </table>
   @endif
</div>

    
@endsection