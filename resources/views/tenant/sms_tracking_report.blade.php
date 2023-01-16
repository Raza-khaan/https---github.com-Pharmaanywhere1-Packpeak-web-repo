@extends('tenant.layouts.mainlayout')
@section('title') <title>Sms Tracking Report</title>
 <style>
  .dropzone {
    min-height: 150px;
    border: 2px dotted rgba(0, 0, 0, 0.3);
    background: white;
    padding: 20px 59px;
}


 .btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}

 </style>
@endsection
@section('content')
 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
      

         <!-- Main content -->
         <section class="content">
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
            <div class="col-xs-12">


              <div class="box">
                
                <div class="box-body pre-wrp-in table-responsive">
                 
                  <table id="example1" class="table table-bordered table-striped display nowrap" style="width:100%">
                    <thead>
                      <tr>

                      <th></th><th style="width: 150px;"></th>

                      <th colspan="2">{{__('January')}}</th>
                      <th colspan="2">{{__('February')}}</th>
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
                      @forelse($notes_for_patients as $notes_for_patient)
                      
                      <tr>

                        <td>{{++$i}}</td>
                        <td style="width: 150px;">{{$notes_for_patient->patients->first_name.' '.$notes_for_patient->patients->last_name}}</td>
                        @foreach($sms_trackings[$notes_for_patient->patient_id] as $sms_tracking)
                          @php
                            $total=$sms_tracking['total']>0?$sms_tracking['total']+1 : $sms_tracking['total'];
                          @endphp
                          <td>{{$total>0?$total:'-'}}</td>
                          <td>{{$total>0?($total)*(0.2):'-'}}</td>
                        @endforeach
                      </tr>
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
      
      $(document).ready(function()
{
  $("#lblmainheading").html("SMS Tracking Report");
});
    </script>
@endsection
