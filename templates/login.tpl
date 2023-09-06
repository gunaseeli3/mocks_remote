<div class="app-container app-theme-white body-tabs-shadow">
  <div class="app-container">
    <div class="h-100">
        <div class="h-100 no-gutters row">
            <div class="d-none d-lg-block col-lg-4">
                <div class="slider-light">
                    <div class="slick-slider">
                        <div>
                            <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-plum-plate" tabindex="-1">
                                <div class="slide-img-bg" style="background-image: url('assets/images/originals/city.jpg');"></div>
                                <div class="slider-content"></div>
                            </div>
                        </div>
                        <div>
                            <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-premium-dark" tabindex="-1">
                                <div class="slide-img-bg" style="background-image: url('assets/images/originals/citynights.jpg');"></div>
                                <div class="slider-content"></div>
                            </div>
                        </div>
                        <div>
                            <div class="position-relative h-100 d-flex justify-content-center align-items-center bg-sunny-morning" tabindex="-1">
                                <div class="slide-img-bg" style="background-image: url('assets/images/originals/citydark.jpg');"></div>
                                <div class="slider-content"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="h-100 d-flex bg-white justify-content-center align-items-center col-md-12 col-lg-8">
                <div class="mx-auto app-login-box col-sm-12 col-md-10 col-lg-9">
                    <div class="app-logo"></div>
                    <h4 class="mb-0">
                        <span class="d-block">Bienvenido</span>
                        <span>Por favor inicia sesión, para poder continuar</span></h4>      
                    <div class="">{$error_0001}</div>
                    <div class="divider row"></div>
                    <div>
                        <form method="POST" name="start_session" action="validate_login.php">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="usuario" class="">Usuario</label><input name="usuario" id="usuario" placeholder="Digita tu nombre de usuario" type="text" class="form-control" required></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="examplePassword" class="">Contraseña</label><input name="password" id="examplePassword" placeholder="Digita tu contraseña" type="password" class="form-control" required></div>
                                </div>
                            </div>
                            <div class="position-relative form-check">
                              <label for="exampleCheck" class="form-check-label"> Mantenerme conectado</label> 
                              <input type="checkbox" checked data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="success" data-offstyle="danger" name="online">
                          </div>
                            <div class="divider row"></div>
                            <div class="d-flex align-items-center">
                                <div class="ml-auto"><a data-toggle="modal" href="#exampleModal" class="btn-lg btn btn-link">Recupera tu contraseña</a>
                                    <input type="submit" class="btn btn-primary btn-lg" name="empezar" id="inicio_sesion" value="Iniciar Sesión">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
				<div class="modal-content element-block-example">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Restablecer contraseña</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
						<form action="">
            <div class="modal-body">							
							<label>Ingrese su correo</label>
               	<input type="email" id="email" class="form-control" required>
            </div>						
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary block-element-btn-example-2" id="rest" type="submit">Restablecer</button>
						</form>	
            </div>
    </div>
</div>
</div>
	<div class="body-block-example-1 d-none">
		<div class="loader">
				<div class="ball-spin-fade-loader">
						<div class="bg-success"></div>
				</div>
		</div>
  </div>
