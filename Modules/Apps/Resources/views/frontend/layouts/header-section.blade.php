  <!-- header start -->
  <header class="site-header site-header-2">
      <div class="nav-area">
          <div class="container-fluid">
              <div class="row align-items-lg-center align-items-start">
                  <div class="col-xl-2 col-lg-2 my-auto">
                      <div class="header-info-left-wrap">
                          <div class="header-info-left">
                              <a href="{{ route('frontend.home') }}" class="site-logo">
                                  <img src="{{ config('setting.images.logo') ? url(config('setting.images.logo')) : url('frontend/assets/images/logo/logo.png') }}"
                                      alt="Cleanixer">
                              </a>
                          </div>
                          <div class="mobile-menu"></div>
                      </div>
                  </div>
                  <div class="col-xl-7 col-lg-7 d-none d-xl-block d-lg-block">
                      <div class="main-menu">
                          <nav id="mobile-menu">
                              <ul>
                                  <li>
                                      <a href="{{ route('frontend.home') }}">
                                          {{ __('Home') }}
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ $aboutUs ? route('frontend.pages.index', $aboutUs->slug) : '#' }}">
                                          {{ __('About Us') }}
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('frontend.offers.index') }}">
                                          {{ __('Offers') }}
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('frontend.baqat.index') }}">
                                          {{ __('Subscriptions') }}
                                      </a>
                                  </li>
                                  <li>
                                      <a href="{{ route('frontend.contact_us') }}">
                                          {{ __('Contact Us') }}
                                      </a>
                                  </li>
                              </ul>
                          </nav>
                      </div>
                  </div>
                  <div class="col-xl-3 d-none d-xl-block d-lg-block my-auto col-lg-3">
                      <div class="header-info-right">

                          @if (auth()->check())

                              <div class="header-lang">
                                  <div class="lang-icon">
                                      <a href="javascript:;" class="site-btn font-lang"> {{ __('My Account') }} <span
                                              class="icon"><i class="fal fa-user"></i></span></a>
                                  </div>
                                  <ul class="header-lang-list">
                                      <li>
                                          <a href="{{ route('frontend.profile.index') }}"><i class="fal fa-user"></i>
                                              {{ __('Profile') }}</a>
                                      </li>
                                      <li>
                                          <a href="{{ route('frontend.profile.edit') }}"><i
                                                  class="fal fa-file-alt"></i>
                                              {{ __('Settings') }}</a>
                                      </li>
                                      <li>
                                          <a href="{{ route('frontend.orders.index') }}"><i
                                                  class="fal fa-shopping-bag"></i>
                                              {{ __('Orders') }}</a>
                                      </li>
                                      <li>
                                          <a onclick="event.preventDefault();document.getElementById('logout').submit();"
                                              href="javascript:;"><i class="fal fa-arrow-alt-to-left"></i>
                                              {{ __('Log Out') }}
                                          </a>
                                          <form id="logout" action="{{ route('frontend.logout') }}" method="POST">
                                              @csrf
                                          </form>

                                      </li>
                                  </ul>
                              </div>
                          @else
                              @if (env('LOGIN'))
                                  <div class="header-lang">
                                      <div class="lang-icon">
                                          <a href="{{ route('frontend.login') }}" class="site-btn font-lang">
                                              {{ __('Login') }} <span class="icon"><i
                                                      class="fal fa-user"></i></span></a>
                                      </div>
                                  </div>
                              @endif
                          @endif

                          <div class="header-lang">
                              <div class="lang-icon">

                                  @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
                                      @if ($localeCode != locale())
                                          <a hreflang="{{ $localeCode }}"
                                              href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                              class="site-btn">
                                              {{ $properties['native'] }} <span class="icon">
                                                  <img src="{{ asset('frontend/assets/images/icons/kuwait.png') }}"
                                                      alt="{{ $properties['native'] }}">
                                              </span>
                                          </a>
                                      @endif
                                  @endforeach

                              </div>
                          </div>

                      </div>
                  </div>
                  <!--Mobile Design Responsive-->
                  <div class="mobile-menu-icon">
                      <div class="col-xl-6 d-block d-xl-none d-lg-none my-auto col-lg-6">

                          <div class="header-info-right">

                              @if (auth()->check())

                                  <div class="header-button-list header-lang">
                                      <div class="lang-icon">
                                          <a href="javascript:;" class="btn font-lang"><i class="fal fa-user"></i></a>
                                      </div>
                                      <ul class="header-lang-list">
                                          <li>
                                              <a href="{{ route('frontend.profile.index') }}"><i
                                                      class="fal fa-user"></i>
                                                  {{ __('Profile') }}</a>
                                          </li>
                                          <li>
                                              <a href="{{ route('frontend.profile.edit') }}"><i
                                                      class="fal fa-file-alt"></i>
                                                  {{ __('Settings') }}</a>
                                          </li>
                                          <li>
                                              <a href="{{ route('frontend.orders.index') }}"><i
                                                      class="fal fa-shopping-bag"></i>
                                                  {{ __('Orders') }}</a>
                                          </li>
                                          <li>
                                              <a onclick="event.preventDefault();document.getElementById('logout').submit();"
                                                  href="javascript:;"><i class="fal fa-arrow-alt-to-left"></i>
                                                  {{ __('Log Out') }}
                                              </a>
                                              <form id="logout" action="{{ route('frontend.logout') }}"
                                                  method="POST">
                                                  @csrf
                                              </form>
                                          </li>
                                      </ul>
                                  </div>
                              @else
                                  @if (env('LOGIN'))
                                      <div class="header-button-list header-lang">
                                          <div class="lang-icon">
                                              <a href="{{ route('frontend.login') }}" class="btn font-lang">
                                                  <i class="fal fa-user"></i>
                                              </a>
                                          </div>
                                      </div>
                                  @endif
                              @endif

                              <div class="header-button-list header-lang">
                                  <div class="lang-icon">
                                      @foreach (config('laravellocalization.supportedLocales') as $localeCode => $properties)
                                          @if ($localeCode != locale())
                                              <a class="btn" hreflang="{{ $localeCode }}"
                                                  href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                                  <img src="{{ asset('frontend/assets/images/icons/kuwait.png') }}"
                                                      alt="{{ $properties['native'] }}">
                                              </a>
                                          @endif
                                      @endforeach
                                  </div>
                              </div>

                          </div>

                      </div>
                  </div>
              </div>
          </div>
      </div>
  </header>
  <!-- header end -->
