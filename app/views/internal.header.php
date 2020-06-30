    <div class="header-container">
      <a href="#" data-target="nav-mobile">
        <i class="material-icons">menu</i>
      </a>
    </div>
    <ul id="nav-mobile" class="sidenav hide">
        <li class="logo">
          <a id="logo-container" href="/" class="brand-logo">
            Finanzas<br />Bonos
          </a>
        </li>
        <li class="info">
          Bienvenid@, <b><?= Identify::g()->data['usuario']['nombres'] ?></b>
        </li>
        <li class="bold"><a href="/" class="waves-effect waves-teal">Inicio</a></li>
        <li class="bold"><a href="/" class="waves-effect waves-teal">Bonos</a></li>
        <li class="bold"><a href="/identificacion?out" class="waves-effect waves-teal">Cerrar Sesión</a></li>
    </ul>
