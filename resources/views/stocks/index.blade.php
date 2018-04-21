@extends('layouts.app')

@section('content')

      <index :items="{{$stocks}}" type="stocks"></index>

@endsection
