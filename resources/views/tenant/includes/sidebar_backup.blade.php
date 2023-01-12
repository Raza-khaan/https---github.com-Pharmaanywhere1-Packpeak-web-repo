@php 

$methodName=str_replace('tenant.','',Route::current()->getName()); 

$menus=App\Helpers\Helper::menus();

@endphp
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
 <!-- sidebar: style can be found in sidebar.less -->
 <section class="sidebar">
   <!-- Sidebar user panel -->
   <div class="user-panel">
     <div class="pull-left image">
     <img src="{{ URL::asset('media/logos/logo2.png') }}" alt="User Image" />
       <!-- <img src="{{ URL::asset('images/users') }}/{{session()->get('image')?session()->get('image'):''}}" class="img-circle" alt="User Image"/> -->
     </div>
     <div class="pull-left info">
       <p>@if(!empty(Session::has('name'))) PickPack @else PickPack  @endif </p>
       <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
     </div>
   </div>

         <!-- sidebar menu: : style can be found in sidebar.less -->
   <ul class="sidebar-menu ">


      @php 
    $differ_day=3;
    $admin_parmacy=App\Models\Tenant\Company::where('id','1')->first();
    $current_date=\Carbon\Carbon::now()->format('Y-m-d');
    $start_date = \Carbon\Carbon::createFromFormat('Y-m-d',$current_date);

    $end_date=\Carbon\Carbon::createFromFormat('Y-m-d',$admin_parmacy->expired_date)->format('Y-m-d');
    $different_days = $start_date->diffInDays($end_date);
    
  @endphp
          @if( $current_date <  $admin_parmacy->expired_date || 
           ( $admin_parmacy->expired_date < $start_date && $different_days <= ($differ_day-1) ) )


     <!-- <li class="header">MAIN NAVIGATION</li> -->
     @php $innerUrl=''; $continue=0; @endphp
     @foreach($menus as $menu)
        
       
          @php 

            if(count($menu['submenu'])){

              $innerUrl=$methodName==$menu['menu']->url;

                if(!$innerUrl){
                    $innerSubMenu=$menu['submenu'];
                    $continue=0;
                    foreach($innerSubMenu as $submenu){
                      if($submenu->url=='technician' && request()->get('role_type')=='technician')
                      {
                        $continue=1;
                        break;
                      }

                      $innerUrl=$methodName==$submenu->url || ($methodName==($submenu->url.'/edit/{id}'));
                      if($innerUrl){ break; }
                    }
                }

            }
          if($continue==1){
              continue;
          }
          @endphp
        
          <li class="treeview {{$innerUrl?'active':''}}">
            <a href="{{url(empty($menu['menu']->url)?'#':$menu['menu']->url)}}">
              <i class="fa {{$menu['menu']->icon}}"></i><span>{{$menu['menu']->name}}</span>
              @if(count($menu['submenu']))
              <i class="fa fa-angle-left pull-right"></i>
              @endif
            </a>
            @if(count($menu['submenu']))
              <ul class="treeview-menu">
              @foreach($menu['submenu'] as $submenu)
                <li class="{{$methodName==$submenu->url || ($methodName==($submenu->url.'/edit/{id}'))?'active':''}}"><a href="{{url($submenu->url)}}" ><i class="fa {{$submenu->icon}}"></i>{{$submenu->name}}</a></li>
              @endforeach
              </ul>
            @endif
          </li>
      
     @endforeach
              

     @endif
   </ul>
 </section>
 <!-- /.sidebar -->
</aside>


