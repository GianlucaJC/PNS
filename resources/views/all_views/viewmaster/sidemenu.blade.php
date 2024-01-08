
<?php
use App\Models\User;
	$id = Auth::user()->id;
	$user = User::find($id);
?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link">
      
	  
	  <img src="{{ URL::asset('/') }}dist/img/pns.png" alt="PNS Logo" class="brand-image img-circle elevation-5" style="opacity: 5;" >
	 
      
	  
	  
	  <span class="brand-text font-weight-light">
		<small>Prodotti non standard</small>
	</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
			@if ($user->hasRole('admin'))
				<img src="{{ URL::asset('/') }}dist/img/AdminLTELogo.png" class="img-circle elevation-2" alt="User Image">
			@elseif ($user->hasRole('coord'))
				<img src="{{ URL::asset('/') }}dist/img/coord.png" class="img-circle elevation-2" alt="User Image">
			@elseif ($user->hasRole('resp'))
				<img src="{{ URL::asset('/') }}dist/img/resp.png" class="img-circle elevation-2" alt="User Image">
			@else	
				<img src="{{ URL::asset('/') }}dist/img/avatar1.png" class="img-circle elevation-2" alt="User Image">
			@endif
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->operatore}}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Cerca" aria-label="Cerca">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
			@if ($user->hasRole('admin'))
			 <li class="nav-item menu">
				<a href="#" class="nav-link">
				  <i class="fas fa-bars"></i>
				  <p>Main Menu
					<i class="right fas fa-angle-left"></i>
				  </p>
				</a>
				<ul class="nav nav-treeview">
				  <li class="nav-item">
					<a href="{{route('elenco_pns')}}" class="nav-link">
					  <i class="far fa-list-alt"></i>
					  <p>Elenco PNS</p>
					</a>
				  </li>
		
					 <li class="nav-item menu">
						<a href="#" class="nav-link">
						  <i class="fas fa-cogs"></i>
						  <p>Archivi
							<i class="right fas fa-angle-left"></i>
						  </p>
						</a>
						<ul class="nav nav-treeview">
						  <li class="nav-item">
							<a href="{{route('gspr')}}" class="nav-link">
							  <i class="far fa-circle nav-icon"></i>
							  <p>Voci GSPR</p>
							</a>
						  </li>
						
						  <li class="nav-item">
							<a href="{{route('risk')}}" class="nav-link ">
							  <i class="far fa-circle nav-icon"></i>
							  <p>Voci RISK</p>
							</a>
						  </li>

						</ul>

					 </li>					  



				</ul>
			  </li>
			 @endif 
			



		  
			 
		  <li class="nav-item">
				<form method="POST" action="{{ route('logout') }}">
					@csrf
					  <li class="nav-item">
						<a href="#" class="nav-link" onclick="event.preventDefault();this.closest('form').submit();">
						  <i class="far fa-circle nav-icon"></i>
						  <p>Logout</p>
						</a>
					  </li>

				</form>	
          </li>
		</ul>  
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
