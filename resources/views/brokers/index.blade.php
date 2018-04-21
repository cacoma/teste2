@extends('layouts.app')

@section('content')

      <index :items="{{$brokers}}" type="brokers"></index>

@endsection
