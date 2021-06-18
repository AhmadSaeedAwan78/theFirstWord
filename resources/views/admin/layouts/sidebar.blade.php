 <div class="app-sidebar__overlay" data-toggle="sidebar"></div>

    <aside class="app-sidebar">

          <?php

      $test = Auth::user()->image_name;
      ?>


        <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" style="max-height: 100px; max-width: 100px;" src="{{asset('assets/profile.jpg')}}" alt="User Image">


          <p class="app-sidebar__user-name">{{Auth::user()->name}}</p>

          <p class="app-sidebar__user-designation">{{Auth::user()->email}}</p>

        </div>

      <ul class="app-menu">

        <!-- <li><a class="app-menu__item <?php if(Request::segment(1) == "dashboard") //echo "active"; ?>" href="{{url('/')}}"><i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Dashboard</span></a></li> -->

        <li><a class="app-menu__item <?php if(Request::segment(1) == "users") //echo "active"; ?>" href="{{url('/category')}}"><i class="app-menu__icon fa fa-user-tie"></i><span class="app-menu__label">{{ __('Catagories') }}</span></a></li>

        <li><a class="app-menu__item <?php if(Request::segment(1) == "users") //echo "active"; ?>" href="{{url('/description')}}"><i class="app-menu__icon fa fa-user-tie"></i><span class="app-menu__label">{{ __('Parents Description') }}</span></a></li>

        <li><a class="app-menu__item" href="{{url('logout')}}"><i class="app-menu__icon fa fa-sign-out"></i><span class="app-menu__label">{{ __('Logout') }}</span></a></li>

		

        

      </ul>

    </aside>