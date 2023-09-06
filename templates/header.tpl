<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
    <div class="app-header header-shadow">
        <div class="app-header__logo">
            <div class="logo-src"></div>
            <div class="header__pane ml-auto">
                <div>
                    <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="app-header__mobile-menu">
            <div>
                <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
        <div class="app-header__menu">
            <span>
                <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                    <span class="btn-icon-wrapper">
                        <i class="fa fa-ellipsis-v fa-w-6"></i>
                    </span>
                </button>
            </span>
        </div>    
        <div class="app-header__content">
            <div class="app-header-left">
                <!------------------------------------------------------------------------- BUSCADOR --------------------------------------------------------------------------------------->
                <div class="search-wrapper">
                    <div class="input-holder">
                        <input type="text" class="search-input" placeholder="Digita para buscar">
                        <button class="search-icon" id="buscar_general"><span></span></button>
                    </div>
                    <button class="close"></button>
                </div>
                
                <!---------------------------------------------------------------------- FIN DE BUSCADOR -------------------------------------------------------------------------------->     
                <!------------------------------------------------------------------------- MEGA MENÚ --------------------------------------------------------------------------------------->                  
                <ul class="header-megamenu nav">              
                    <li class="nav-item">
                        <a href="javascript:void(0);" data-placement="bottom" rel="popover-focus" data-offset="300" data-toggle="popover-custom" class="nav-link">
                            <i class="nav-link-icon pe-7s-gift"> </i>
                            Mega Menú
                            <i class="fa fa-angle-down ml-2 opacity-5"></i>
                        </a>
                        <div class="rm-max-width">
                            <div class="d-none popover-custom-content">
                                <div class="dropdown-mega-menu">
                                    <div class="grid-menu grid-menu-3col">
                                        <div class="no-gutters row">
                                            <div class="col-sm-6 col-xl-4">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-header nav-item">
                                                        Overview
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">
                                                            <i class="nav-link-icon lnr-inbox">
                                                            </i>
                                                            <span>
                                                                Contacts
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">
                                                            <i class="nav-link-icon lnr-book">
                                                            </i>
                                                            <span>
                                                                Incidents
                                                            </span>
                                                            <div class="ml-auto badge badge-pill badge-danger">5
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">
                                                            <i class="nav-link-icon lnr-picture">
                                                            </i>
                                                            <span>
                                                                Companies
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a disabled="" href="javascript:void(0);" class="nav-link disabled">
                                                            <i class="nav-link-icon lnr-file-empty">
                                                            </i>
                                                            <span>
                                                                Dashboards
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-sm-6 col-xl-4">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-header nav-item">
                                                        Favourites
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">
                                                            Reports Conversions
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">
                                                            Quick Start
                                                            <div class="ml-auto badge badge-success">New</div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Users &amp; Groups</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Proprieties</a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-sm-6 col-xl-4">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-header nav-item">Sales &amp; Marketing
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Queues
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Resource Groups
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Goal Metrics
                                                            <div class="ml-auto badge badge-warning">3
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Campaigns
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <!------------------------------------------------------------------------- FIN DE MEGA MENU --------------------------------------------------------------------------------------->                         
            </div>
            <!------------------------------------------------------------------------- NOTIFICACIONES --------------------------------------------------------------------------------------->               
            <div class="app-header-right">
                <div class="header-dots">
                    <div class="dropdown">
                        <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="p-0 mr-2 btn btn-link">
                            <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                <span class="icon-wrapper-bg bg-danger"></span>
                                <i class="icon text-danger icon-anim-pulse ion-android-notifications"></i>
                                <span class="badge badge-dot badge-dot-sm badge-danger"></span>
                            </span>
                        </button>
                        <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu rm-pointers dropdown-menu dropdown-menu-right" style="width:800px;">
                            <div class="dropdown-menu-header mb-0">
                                <div class="dropdown-menu-header-inner bg-deep-blue">
                                    <div class="menu-header-image opacity-1" ></div>
                                    <div class="menu-header-content text-dark">
                                        <h5 class="menu-header-title">Notificaciones</h5>
                                        <h6 class="menu-header-subtitle">Tienes <b id="cantidad_aprobaciones">0</b> por aprobar</h6>
                                    </div>
                                </div>
                            </div>
                            <ul class="tabs-animated-shadow tabs-animated nav nav-justified tabs-shadow-bordered p-3">
                                <li class="nav-item" id="aprobacion_informes">
                                    <a role="tab" class="nav-link" data-toggle="tab" href="#tab-messages-header" id="clickmeame">
                                        <span>Informes</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-messages-header" role="tabpanel">
                                    <div class="scroll-area-sm">
                                        <div class="scrollbar-container">
                                            <div class="p-3">
                                                <div class="notifications-box" style="text-align:center;">
                                                   <table class="table" >
                                                      <thead>
                                                         <tr>
                                                            <th colspan="2">Informe</th>
                                                            <th>Observación</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="resultados_de_aprobaciones">
                                                        
                                                    </tbody>
                                                </table>                                               
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab-events-header" role="tabpanel">
                                <div class="scroll-area-sm">
                                    <div class="scrollbar-container">
                                        <div class="p-3">
                                            <div class="vertical-without-time vertical-timeline vertical-timeline--animate vertical-timeline--one-column">
                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                    <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-success"> </i></span>
                                                        <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">All Hands Meeting</h4>
                                                            <p>Lorem ipsum dolor sic amet, today at <a href="javascript:void(0);">12:00 PM</a></p><span class="vertical-timeline-element-date"></span></div>
                                                        </div>
                                                    </div>
                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-warning"> </i></span>
                                                            <div class="vertical-timeline-element-content bounce-in"><p>Another meeting today, at <b class="text-danger">12:00 PM</b></p>
                                                                <p>Yet another one, at <span class="text-success">15:00 PM</span></p><span class="vertical-timeline-element-date"></span></div>
                                                            </div>
                                                        </div>
                                                        <div class="vertical-timeline-item vertical-timeline-element">
                                                            <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-danger"> </i></span>
                                                                <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">Build the production release</h4>
                                                                    <p>Lorem ipsum dolor sit amit,consectetur eiusmdd tempor incididunt ut labore et dolore magna elit enim at minim veniam quis nostrud</p><span
                                                                    class="vertical-timeline-element-date"></span></div>
                                                                </div>
                                                            </div>
                                                            <div class="vertical-timeline-item vertical-timeline-element">
                                                                <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-primary"> </i></span>
                                                                    <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title text-success">Something not important</h4>
                                                                        <p>Lorem ipsum dolor sit amit,consectetur elit enim at minim veniam quis nostrud</p><span class="vertical-timeline-element-date"></span></div>
                                                                    </div>
                                                                </div>
                                                                <div class="vertical-timeline-item vertical-timeline-element">
                                                                    <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-success"> </i></span>
                                                                        <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">All Hands Meeting</h4>
                                                                            <p>Lorem ipsum dolor sic amet, today at <a href="javascript:void(0);">12:00 PM</a></p><span class="vertical-timeline-element-date"></span></div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="vertical-timeline-item vertical-timeline-element">
                                                                        <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-warning"> </i></span>
                                                                            <div class="vertical-timeline-element-content bounce-in"><p>Another meeting today, at <b class="text-danger">12:00 PM</b></p>
                                                                                <p>Yet another one, at <span class="text-success">15:00 PM</span></p><span class="vertical-timeline-element-date"></span></div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="vertical-timeline-item vertical-timeline-element">
                                                                            <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-danger"> </i></span>
                                                                                <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">Build the production release</h4>
                                                                                    <p>Lorem ipsum dolor sit amit,consectetur eiusmdd tempor incididunt ut labore et dolore magna elit enim at minim veniam quis nostrud</p><span
                                                                                    class="vertical-timeline-element-date"></span></div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="vertical-timeline-item vertical-timeline-element">
                                                                                <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-primary"> </i></span>
                                                                                    <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title text-success">Something not important</h4>
                                                                                        <p>Lorem ipsum dolor sit amit,consectetur elit enim at minim veniam quis nostrud</p><span class="vertical-timeline-element-date"></span></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane" id="tab-errors-header" role="tabpanel">
                                                                <div class="scroll-area-sm">
                                                                    <div class="scrollbar-container">
                                                                        <div class="no-results pt-3 pb-0">
                                                                            <div class="swal2-icon swal2-success swal2-animate-success-icon">
                                                                                <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                                                                <span class="swal2-success-line-tip"></span>
                                                                                <span class="swal2-success-line-long"></span>
                                                                                <div class="swal2-success-ring"></div>
                                                                                <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                                                                <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                                                                            </div>
                                                                            <div class="results-subtitle">All caught up!</div>
                                                                            <div class="results-title">There are no system errors!</div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!------------------------------------------------------------------------- FIN DE NOTIFICACIONES --------------------------------------------------------------------------------------->
                                                <!------------------------------------------------------------------------- ACTIVIDAD --------------------------------------------------------------------------------------->       
                                                <div class="dropdown">
                                                    <button type="button" aria-haspopup="true" data-toggle="dropdown" aria-expanded="false" class="p-0 btn btn-link dd-chart-btn">
                                                        <span class="icon-wrapper icon-wrapper-alt rounded-circle">
                                                            <span class="icon-wrapper-bg bg-success"></span>
                                                            <i class="icon text-success ion-ios-analytics"></i>
                                                        </span>
                                                    </button>
                                                  
                                                    <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-xl rm-pointers dropdown-menu dropdown-menu-right">
                                                        <div class="dropdown-menu-header">
                                                            <div class="dropdown-menu-header-inner bg-premium-dark">
                                                                <div class="menu-header-image" style="background-image: url('assets/images/dropdown-header/abstract4.jpg');"></div>
                                                                <div class="menu-header-content text-white">
                                                                    <h5 class="menu-header-title">Sensores
                                                                    </h5>
                                                                    <h6 class="menu-header-subtitle">Recent Account Activity Overview
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="widget-chart">
                                                            <div class="widget-chart-content">
                                                                <div class="icon-wrapper rounded-circle">
                                                                    <div class="icon-wrapper-bg opacity-9 bg-focus">
                                                                    </div>
                                                                    <i class="lnr-users text-white">
                                                                    </i>
                                                                </div>
                                                                <div class="widget-numbers">
                                                                    <span>344k</span>
                                                                </div>
                                                                <div class="widget-subheading pt-2">
                                                                    Profile views since last login
                                                                </div>
                                                                <div class="widget-description text-danger">
                                                                    <span class="pr-1">
                                                                        <span>176%</span>
                                                                    </span>
                                                                    <i class="fa fa-arrow-left"></i>
                                                                </div>
                                                            </div>
                                                            <div class="widget-chart-wrapper">
                                                                <div id="dashboard-sparkline-carousel-3-pop"></div>
                                                            </div>
                                                        </div>
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item-divider mt-0 nav-item">
                                                            </li>
                                                            <li class="nav-item-btn text-center nav-item">
                                                                <button class="btn-shine btn-wide btn-pill btn btn-warning btn-sm">
                                                                    <i class="fa fa-cog fa-spin mr-2">
                                                                    </i>
                                                                    View Details
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <!------------------------------------------------------------------------- DATOS DE USUARIO LOGEADO --------------------------------------------------------------------------------------->                      
                                            <div class="header-btn-lg pr-0">
                                                <div class="widget-content p-0">
                                                    <div class="widget-content-wrapper">
                                                        <div class="widget-content-left">
                                                            <div class="btn-group">
                                                                <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="p-0 btn">
                                                                    <h2>ssssssssssssss</h2>
                                                                    <!--<img width="42" class="rounded-circle" src="{$imagen}" alt="">-->
                                                                    <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                                                </a>
                                                                <div tabindex="-1" role="menu" aria-hidden="true" class="rm-pointers dropdown-menu-lg dropdown-menu dropdown-menu-right">
                                                                    <div class="dropdown-menu-header">
                                                                        <div class="dropdown-menu-header-inner bg-info">
                                                                            <div class="menu-header-image opacity-2"></div>
                                                                            <div class="menu-header-content text-left">
                                                                                <div class="widget-content p-0">
                                                                                    <div class="widget-content-wrapper">
                                                                                        <div class="widget-content-left mr-3">
                                                                                         <a href="#" title="Mi Cuenta"><h2>ssssssssssssss</h2>
                                                                <!--<img width="42" class="rounded-circle"
                                                                     src="{$imagen}"
                                                                     alt="">-->
                                                                 </a>	
                                                             </div>
                                                             <div class="widget-content-left">
                                                                <div class="widget-heading">{$mi_nombre}
                                                                </div>
                                                                <div class="widget-subheading opacity-8">{$mi_cargo}
                                                                </div>
                                                            </div>
                                                            <div class="widget-content-right mr-2">
                                                                <button id="btn_cerrar_sesion" class="btn-pill btn-shadow btn-shine btn btn-focus">Salir
                                                                </button> 
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="scroll-area-xs" style="height:100px;">
                                            <div class="scrollbar-container ps">
                                                <ul class="nav flex-column">
                                                    <li class="nav-item-header nav-item">Actividades
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Chat
                                                            <div class="ml-auto badge badge-pill badge-info">
                                                            </div>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a href="javascript:void(0);" class="nav-link">Cambiar Contraseña
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <ul class="nav flex-column">
                                            <li class="nav-item-divider mb-0 nav-item"></li>
                                        </ul>
                                        <div class="grid-menu grid-menu-2col">
                                            <div class="no-gutters row">
                                                <div class="col-sm-6">
                                                    <button class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-warning">
                                                        <i class="pe-7s-chat icon-gradient bg-amy-crisp btn-icon-wrapper mb-2"></i>
                                                        Message Inbox
                                                    </button>
                                                </div>
                                                <div class="col-sm-6">
                                                    <button class="btn-icon-vertical btn-transition btn-transition-alt pt-2 pb-2 btn btn-outline-danger">
                                                        <i class="pe-7s-ticket icon-gradient bg-love-kiss btn-icon-wrapper mb-2"></i>
                                                        <b>Support Tickets</b>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="nav flex-column">
                                            <li class="nav-item-divider nav-item">
                                            </li>
                                            <li class="nav-item-btn text-center nav-item">
                                                <button class="btn-wide btn btn-primary btn-sm">
                                                    Open Messages
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content-left  ml-3 header-user-info">
                                <div class="widget-heading">																
                                   {$mi_nombre}
                               </div>
                               <div class="widget-subheading">
                                 {$mi_cargo}
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <!------------------------------------------------------------------------- FIN DE DATOS DE USUARIO LOGEADO --------------------------------------------------------------------------------------->               

         </div>
     </div>
 </div> 

 <script type="text/javascript" src="design/js/backtrack.js"></script>

 