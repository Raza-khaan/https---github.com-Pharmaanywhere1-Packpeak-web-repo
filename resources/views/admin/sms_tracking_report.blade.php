@extends('admin.layouts.mainlayout')
@section('title') <title>Sms Tracking Report</title>
<style>
  #example1_wrapper {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-left: 20px;
}
  </style>
@endsection





@section('content')
<div class="content-wrapper">
        <div class="dash-wrap">
          <div class="dashborad-header">
            <a id="menu-bar" href="#"><i class="fa fa-bars"></i></a>
            <h2>SMS Tracking Report</h2>
            <a class="small-logo-mobile" href="#"><img src="assets/images/mobile-logo.png" alt=""></a>
            <div class="user-menu">
              
               <div class="profile"> 
                  <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <img src="assets/images/user.png" alt=""> <span>Amir Eid</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <a class="dropdown-item" href="#">My Profile</a>
                      <a class="dropdown-item" href="#">Setting</a>
                      <a class="dropdown-item" href="#">Logout</a>
                    </div>
                  </div>
                  <p class="online"><span></span>Online</p>
                </div>
            </div>
          </div>

          <div class="pharma-register">
              <h2>Search Results</h2>
          </div>

          <div class="reports-breadcrum m-0">

          <nav class="dash-breadcrumb" aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html"><img src="assets/images/icon-home.png" alt="">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Forms</li>
                <li class="breadcrumb-item active" aria-current="page">General Elemenst</li>
              </ol>
            </nav>

          </div>
       
          <style>
                        .dt-buttons button{
                          background: rgb(192, 229, 248) !important;
                        border-color: rgb(255, 255, 255) !important;
                        color: blue;
                        font-weight: italic;
                        color: #1f89bb;
                       
/* right: -1062%;
    bot    tom: 90; */
                        }
                        .btn-group, .btn-group-vertical{
  flex-direction: column !important;
}
                        
                        </style>

   
       
       

          <!-- Main content -->
          <section class="content"  style="background-color: #ffffff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);
    -webkit-box-shadow: 0px 3px 20px rgb(0 0 0 / 6%);">
            <div class="row">
              @if(Session::has('msg'))
                {!!  Session::get("msg") !!}
              @endif
              @if($errors->any())
                  <div class="alert alert-danger">
                      <ul>
                          @foreach ($errors->all() as $error)
                              <li>{{ $error }}</li>
                          @endforeach
                      </ul>
                  </div>
              @endif
              <div class="col-md-12">
                <div class="sms-tracking-report">
                  <div class="sms-tracking">
                  <h3>SMS Tracking Report</h3><br>
                    <div class="pull-right alertmessage"></div>
                  </div><!-- /.box-header -->

                  <div class="box-body pre-wrp-in table-responsive">
                  
                    <table id="example1" class="table table-bordered table-striped">
                      <thead>
                        <tr>

                        <th></th><th style="width: 150px;"></th>

                        <th colspan="3">{{__('January')}}</th>
                        <th colspan="2">{{__('Febraury')}}</th>
                        <th colspan="2">{{__('March')}}</th>
                        <th colspan="2">{{__('April')}}</th>
                        <th colspan="2">{{__('May')}}</th>
                        <th colspan="2">{{__('June')}}</th>
                        <th colspan="2">{{__('July')}}</th>
                        <th colspan="2">{{__('August')}}</th>
                        <th colspan="2">{{__('September')}}</th>
                        <th colspan="2">{{__('October')}}</th>
                        <th colspan="2">{{__('November')}}</th>
                        <th colspan="2">{{__('December')}}</th>

                        </tr>
                        <tr>
                          <th>S.No</th>
                          <th style="width: 150px;">Pharmacy Name</th>
                          <th style="width: 150px;">Patient Name</th>

                          <th>Total No</th>
                          <th>Amount</th>

                          <th>Total No</th>
                          <th>Amount</th>

                          <th>Total No</th>
                          <th>Amount</th>

                          <th>Total No</th>
                          <th>Amount</th>

                          <th>Total No</th>
                          <th>Amount</th>

                          <th>Total No</th>
                          <th>Amount</th>

                          <th>Total No</th>
                          <th>Amount</th>

                          <th>Total No</th>
                          <th>Amount</th> 

                          <th>Total No</th>
                          <th>Amount</th>

                          <th>Total No</th>
                          <th>Amount</th>

                          <th>Total No</th>
                          <th>Amount</th>

                          <th>Total No</th>
                          <th>Amount</th>

                      
                        </tr>
                      </thead>
                      <tbody>
                        @php $i=0;
                        @endphp
                        @forelse($sms_trackings as $pharmacyName=>$sms_tracking)
                       
                          @foreach($sms_tracking as $patient_id=>$sms)
                         
                            <tr>
                            
                                <td>{{++$i}}</td>
                                <td>{{$pharmacyName}}</td>
                                <td style="width: 150px;">{{$patients_names[$pharmacyName][$patient_id]->first_name .' '.$patients_names[$pharmacyName][$patient_id]->last_name}}</td>
                                @foreach($sms as $month)
                                  @php
                                  
                                    $total=$month['total']>0?$month['total']+1 : $month['total'];
                                  @endphp
                                  <td>{{$total>0?$total:'-'}}</td>
                                  <td>{{$total>0?($total)*(0.2):'-'}}</td>
                                @endforeach
                            </tr>
                          
                          @endforeach

                          @empty
                          <tr><td colspan="26">{{__('No Records')}}</td></tr>

                        @endforelse
                        

                      </tbody>
                    
                    </table>


                  
                  </div><!-- /.box-body -->

                </div><!-- /.box -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </section><!-- /.content -->

      </div><!-- /.content-wrapper -->


@endsection


@section('customjs')


   
<script type="text/javascript">
      //  For   Bootstrap  datatable 
      $(function () {

        $('#example1').DataTable( {
          lengthChange: true,
          language: {
               // search: '<i class="fa fa-search"></i>',
                searchPlaceholder: "search",
               },

          lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],     // page length options
          columnDefs: [ {
            orderable: false,
            sorting: false,
            className: 'select-checkbox',
            targets:   0
            } ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            dom: '<"top"if>Brt<"bottom"p>l',
            // dom: 'f<>Brtpl',
            buttons: [
               
                {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'copy',
                    'excel',
                    'csv',
                    'pdf',
                    'print',
                    'pageLength','colvis',
                    'pageLength','colvis'
                ]
                },
            ],
             //select: true,
        });
        
      });

    </script>
@endsection
