@extends('layouts.app')

@section('content')
<div class="row">
   <div class="center-block" >
      {!! QrCode::size(100)->generate($url);!!}
   </div>
</div>
    
@endsection