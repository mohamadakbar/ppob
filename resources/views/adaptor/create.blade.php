@extends('layouts.sandeza')
@section('content')
<div class="panel panel-default">
  <div class="panel-body">
    {!! Form::open(['id' => 'form-default', 'postmethod' => 'Added', 'posturl' => route('adaptor.link'),'method' => 'post','files'=>'true','class'=>'form-horizontal']) !!}
    @include('adaptor._form')
    {!! Form::close() !!}
  </div>
</div>
@endsection
