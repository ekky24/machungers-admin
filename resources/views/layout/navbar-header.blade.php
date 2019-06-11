<header class="main-header">

    <!-- Logo -->
    <a href="index2.html" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>WA</b>M</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>WeAre</b>Machungers</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#">
              <span class="hidden-xs">{{ session()->get('authenticated')['username'] }}</span>
            </a>
          </li>
          <li class="dropdown messages-menu">
            <a href="/setting/form">
              <i class="fa fa-gear"></i>
            </a>
          </li>
          <li class="dropdown messages-menu">
            <a href="/logout">
              <i class="fa fa-sign-out"></i>
            </a>
          </li>
        </ul>
      </div>

    </nav>
  </header>