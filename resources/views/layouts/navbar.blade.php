<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Cacoma
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
               <!-- Left Side Of Navbar -->
              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                  <b-navbar-nav>
                      <b-nav-item href="/home">Home</b-nav-item>
                      <b-nav-item href="/invests">Invest</b-nav-item>
                  </b-navbar-nav>

<!--                     <ul class="navbar-nav mr-auto">
                      <li class="nav-item active"> -->
<!--                         <a class="nav-link" href="/home">Home <span class="sr-only">(current)</span></a>
                        <a class="nav-link" href="/home">Home</span></a>
                        <a class="nav-link" href="/invests">Invest</span></a>-->
                  
<!--                       </li> -->
<!--                       @guest
                      @else
                      @if (Auth::user()->role_id === 1) -->
                        <navbaradmin :auth="{{ auth()->user() }}"></navbaradmin>
<!--                       @endif
                    @endguest -->
<!--                     </ul> -->


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
