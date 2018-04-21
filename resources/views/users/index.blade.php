@extends('layouts.app')

@section('content')
    
      <index :items="{{$users}}" type="users"></index>

@endsection
