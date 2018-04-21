@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <!-- @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif -->

<!--                   @foreach ($monthlyQuotes as $monthlyQuote)
                    <p>Open {{ $monthlyQuote->open }}</p>
                @endforeach -->
<!--                         <div class="row">
                          <div class="col-sm-6">
                            <div class="card">
                              <div class="card-body">
                                <h5 class="card-title">Card 1</h5>
                                <p class="card-text">Sobre data:</p>
                                <homechart :data="{{$monthlyQuotes}}" :width="400" :height="200"></homechart>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="card">
                              <div class="card-body">
                                <h5 class="card-title">Card 2</h5>
                                <p class="card-text">Outro</p>
                                 <homechartpizza :data="{{$invests}}" :width="400" :height="200"></homechartpizza>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card">
                              <div class="card-body">
                                <h5 class="card-title">Card 3</h5>
                                <p class="card-text">Sobre data:</p>
                                 <homechartpizza :data="{{$invests}}" :width="400" :height="200"></homechartpizza>
                                <homechartpizza :data="{{$invests}}" :options="{responsive: false, maintainAspectRatio: false}"></homechartpizza>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                              </div>
                            </div>
                          </div> -->
<!--                           <div class="col-sm-6">
                            <div class="card">
                              <div class="card-body">
                                <h5 class="card-title">Card 4</h5>
                                <p class="card-text">Outro</p>
                                 <homechart :data="{{$monthlyQuotes}}" :width="400" :height="200"></homechart>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                              </div>
                            </div>
                          </div>
                        </div> -->
                  <homescreen :invests="{{$invests}}" :monthlyquotes="{{$monthlyQuotes}}" :results="{{$results}}" :forcharts="{{$forCharts}}" :pie="{{json_encode($pie)}}"></homescreen>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
