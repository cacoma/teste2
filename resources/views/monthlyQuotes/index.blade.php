@extends('layouts.app')

@section('content')

      <index :items="{{$monthlyQuotes}}" type="monthlyquotes"></index>

@endsection