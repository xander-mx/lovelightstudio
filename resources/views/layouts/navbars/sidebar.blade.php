<div class="sidebar" data-image="{{ asset('assets/img/sidebar-5.jpg') }}" style="background: #ffc0cb;
">
    <!--
Tip 1: You can change the color of the sidebar using: data-color="purple | blue | green | orange | red"

Tip 2: you can also add an image using data-image tag
-->
    <div class="sidebar-wrapper" style="
    background: #ffc0cb;
">
        <div class="logo" style="background-color: white; border: #0c0c0c; border-style: solid; border-top-style: hidden;
         border-width: thin; border-color: lightgrey;" >
            <a href="https://lovelight.com.mx/" class="simple-text">
                <img src="{{ asset('logo/love-light-studio-logo.jpg') }}" style="align-content: center"
                     alt="Love Light Studio" width="230px">
            </a>
        </div>
        <ul class="nav">
            <li class="nav-item @if($activePage == 'dashboard') active @endif">
                <a class="nav-link" href="{{route('dashboard')}}">
                    <i class="nc-icon nc-chart-pie-35"></i>
                    <p>{{ __("Dashboard") }}</p>
                </a>
            </li>

            @if (Auth::user()->idPerfil == 1 || Auth::user()->idPerfil == 2 ||Auth::user()->idPerfil == 8 )
                <li class="nav-item @if($activePage == 'trabajador') active @endif">
                    <a class="nav-link" href="{{route('trabajador.index')}}">
                    {{-- <a class="nav-link" href=""> --}}
                        <i class="nc-icon nc-bullet-list-67"></i>
                        <p>{{ __("Trabajadores") }}</p>
                    </a>
                </li>
            @endif
            <li class="nav-item @if($activePage == 'reservas.create') active @endif">
                <a class="nav-link" href="{{route('reserva.create')}}">
                    <i class="nc-icon nc-simple-add"></i>
                    <p>{{ __("Nueva Reserva") }}</p>
                </a>
            </li>
            <li class="nav-item @if($activePage == 'reserva.index') active @endif">
                <a class="nav-link" href="{{route('reserva.index')}}">
                    <i class="nc-icon nc-circle-09"></i>
                    <p>{{ __("reservas activas") }}</p>
                </a>
            </li>
            <li class="nav-item @if($activePage == 'reserves.sold.index') active @endif">
                <a class="nav-link" href="{{route('reserves.sold.index')}}">
                    <i class="bi bi-cart-check"></i>
                    <p>{{ __("vendidas") }}</p>
                </a>
            </li>
            @if (Auth::user()->idPerfil == 1 || Auth::user()->idPerfil == 2 ||Auth::user()->idPerfil == 8 )
                <li class="nav-item @if($activePage == 'reserva.historial.eliminada') active @endif">
                    <a class="nav-link" href="{{route('reserva.historial.eliminada')}}">
                        <i class="bi bi-cart-x"></i>
                        <p>{{ __("canceladas") }}</p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->idPerfil == 1)
                <li class="nav-item @if($activePage == 'trabajador') active @endif">
                    <a class="nav-link" href="{{route('sueldo.index')}}">
                        <i class="nc-icon nc-bag"></i>
                        <p>{{ __("Sueldo") }}</p>
                    </a>
                </li>
            @endif
            @if (Auth::user()->idPerfil == 1)
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#payroll-menu" @if($activeButton == 'commissions') aria-expanded="true" @endif>
                        <i class="bi bi-cash-stack"></i>
                        <p>
                            {{ __('Nómina') }}
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse @if($activeButton =='commissions') show @endif" id="payroll-menu">
                        <ul class="nav pl-2">
                            <li class="nav-item @if($activePage == 'commissions.index') active @endif">
                                <a class="nav-link" href="{{route('web.commissions.index')}}">
                                    <i class="bi bi-card-checklist"></i>
                                    <p>{{ __("Comisiones") }}</p>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav pl-2">
                            <li class="nav-item @if($activePage == 'payrolls.index') active @endif">
                                <a class="nav-link" href="{{route('web.payrolls.index')}}">
                                    <i class="bi bi-journals"></i>
                                    <p>{{ __("Arqueos") }}</p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            @endif
            @if (Auth::user()->idPerfil == 1 || Auth::user()->idPerfil == 10)
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#weddings-menu" @if($activeButton =='weddings') aria-expanded="true" @endif>
                    <i class="bi bi-gem"></i>
                    <p>
                        {{ __('Bodas') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse @if($activeButton =='weddings') show @endif" id="weddings-menu">
                    <ul class="nav pl-2">
                        <li class="nav-item @if($activePage == 'weddings.services.index') active @endif">
                            <a class="nav-link" href="{{route('weddings.services.index')}}">
                                <i class="bi bi-journal-album"></i>
                                <p>{{ __("Paquetes") }}</p>
                            </a>
                        </li>
                        <li class="nav-item @if($activePage == 'weddings.events.index') active @endif">
                            <a class="nav-link" href="{{route('weddings.events.index')}}">
                                <i class="bi bi-camera-reels"></i>
                                <p>{{ __("Eventos") }}</p>
                            </a>
                        </li>
                        <li class="nav-item @if($activePage == 'weddings.payments.index') active @endif">
                            <a class="nav-link" href="{{route('weddings.payments.index')}}">
                                <i class="bi bi-cash-coin"></i>
                                <p>{{ __("Pagos") }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @endif
            @if (Auth::user()->idPerfil == 1)
                <li class="nav-item @if($activePage == 'configurations') active @endif">
                    <a class="nav-link" href="{{route('web.configurations.index')}}">
                        <i class="nc-icon nc-settings-tool-66"></i>
                        <p>{{ __("Configuraciones") }}</p>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</div>
