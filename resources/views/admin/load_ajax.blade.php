@switch($mode)
    @case('all_patients')
          <option value="">-- Enter Patient--</option>
          @if(isset($all_patients) && count($all_patients))
             @foreach($all_patients as $row)
             <option value="{{$row->id}}" 
             data-dob="{{$row->dob}}" data-lastPickupDate="{{isset($row->latestPickups->created_at)?$row->latestPickups->created_at:''}}"  data-lastPickupWeek="{{isset($row->latestPickups->created_at)?$row->latestPickups->no_of_weeks:''}}"
             data-lastNoteForPatient="{{isset($row->latestPickups->notes_from_patient)?$row->latestPickups->notes_from_patient:''}}"
             data-lastLocation="{{isset($row->latestPickups->location)?$row->latestPickups->location:''}}"
             >{{$row->first_name.' '.$row->last_name}} </option>
             @endforeach
          @endif
    @break
    @case('patients_info')
          <div class="modal-header">
            <span class="modal-title"></span>
             <a href="{{url('admin/edit_patient/'.$patients[0]->website_id.'/'.$patients[0]->id)}}" class="btn btn-default"> <i class="fa fa-edit"></i> Edit</a>
             <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body details_modal_body">
             <table class="table table-bordered table-striped table-hover">
              
              <tr>
                  <th>Date And Time</th>
                  <th>{{date("F j, Y, h:i A",strtotime($patients[0]->created_at))}}</th>
              </tr>
              <tr>
                  <th>Patient</th>
                  <th>{{ucfirst($patients[0]->first_name).' '.ucfirst($patients[0]->last_name)}}</th>
              </tr>
              <tr>
                  <th>DOB</th>
                  <th>{{date("F j, Y",strtotime($patients[0]->dob))}}</th>
              </tr>
              <tr>
                  <th>Facility</th>
                  <th>{{strtoupper($patients[0]->facility->name)}}</th>
              </tr>
              <tr>
                  <th>Location</th>
                  <th>{{$patients[0]->location}}</th>
              </tr>
              <tr>
                  <th>Address</th>
                  <th>{{$patients[0]->address}}</th>
              </tr>
              <tr>
                  <th>Phone</th>
                  <th>{{$patients[0]->phone_number}}</th>
              </tr>
              <tr>
                  <th>Get a text when picked up/Delivered </th>
                  <th>{{($patients[0]->text_when_picked_up_deliver==NUll)?'false':'true'}}</th>
              </tr>

             <table>
          </div>
    @break
    @case('pickup_info')
          <div class="modal-header">
            <span class="modal-title" ></span>
             <a href="{{url('admin/edit_pickup/'.$pickup[0]->website_id.'/'.$pickup[0]->id)}}" class="btn btn-default"> <i class="fa fa-edit"></i> Edit</a>
             <button class="close" data-dismiss="modal">&times;</button>
          </div>

          <div class="modal-body details_modal_body" >
             <table class="table table-bordered table-striped table-hover">
              <tr>
                  <th>Number of Weeks</th>
                  <th>{{$pickup[0]->no_of_weeks}}</th>
              </tr>
              <tr>
                  <th>Carer's Name</th>
                  <th>{{$pickup[0]->carer_name}}</th>
              </tr>
              <tr>
                  <th>Patient Signature</th>
                  <th><img src="{{$pickup[0]->patient_sign}}" style="height: 30px; width: 80px;"></th>
              </tr>
              <tr>
                  <th>Who is picking up?</th>
                  <th>{{ucfirst($pickup[0]->pick_up_by)}}</th>
              </tr>
              <tr>
                  <th>Last Pick Up Date</th>
                  <th>{{($pickup[0]->last_pick_up_date!=null)?date("F j, Y, h:i A",strtotime($pickup[0]->last_pick_up_date)):''}}</th>
              </tr>
              <tr>
                  <th># of Weeks Last Picked Up</th>
                  <th>{{$pickup[0]->weeks_last_picked_up}}</th>
              </tr> 
              <tr>
                  <th>DOB</th>
                  <th>{{date("F j, Y",strtotime($pickup[0]->dob))}}</th>
              </tr>
              <tr>
                  <th>Date Time</th>
                  <th>{{date("F j, Y, h:i A",strtotime($pickup[0]->created_at))}}</th>
              </tr>
              <tr>
                  <th>Notes From Patient</th>
                  <th>{{$pickup[0]->notes_from_patient}}</th>
              </tr>
              <tr>
                  <th>Name</th>
                  <th>{{ucfirst($pickup[0]->patients->first_name)." ".ucfirst($pickup[0]->patients->last_name)}}</th>
              </tr>
               <tr>
                  <th>Location</th>
                  <th>{{$pickup[0]->location}}</th>
              </tr>
              <tr>
                  <th>Facility</th>
                  <th>{{strtoupper($pickup[0]->patients->facility->name)}}</th>
              </tr>
              <tr>
                  <th>Pharmacist</th>
                  <th><img src="{{$pickup[0]->pharmacist_sign}}" style="height: 30px;  width: 80px; "></th>
              </tr>
              <tr>
                  <th>Patient Phone</th>
                  <th>{{$pickup[0]->patients->phone_number}}</th>
              </tr>

              
              
             <table>
          </div>
    @break
    @case('return_info')
          <div class="modal-header">
            <span class="modal-title"></span>
             <a href="{{url('admin/edit_return/'.$returns[0]->website_id.'/'.$returns[0]->id)}}" class="btn btn-default"> <i class="fa fa-edit"></i> Edit</a>
             <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body details_modal_body">
             <table class="table table-bordered table-striped table-hover">
              
              <tr>
                  <th>DateTime</th>
                  <th>{{date("F j, Y, h:i A",strtotime($returns[0]->created_at))}}</th>
              </tr>
              <tr>
                  <th>Paient Name</th>
                  <th>{{$returns[0]->patients->first_name.' '.$returns[0]->patients->last_name}}</th>
              </tr>
              <tr>
                  <th>Select Days or Weeks</th>
                  <th>{{$returns[0]->day_weeks}}</th>
              </tr>
              <tr>
                  <th>Number of day/weeks returned</th>
                  <th>{{$returns[0]->returned_in_days_weeks}}</th>
              </tr>
              <tr>
                  <th>Store</th>
                  <th>{{strtoupper($returns[0]->facility->name)}}</th>
              </tr>
              <tr>
                  <th>Staff initials</th>
                  <th>{{$returns[0]->staff_initials}}</th>
              </tr>
              <tr>
                  <th>DOB</th>
                  <th>{{date("F j, Y",strtotime($returns[0]->dob))}}</th>
              </tr>
              

             <table>
          </div>
    @break
    @case('near_miss_info')
          <div class="modal-header">
            <span class="modal-title"></span>
             <a href="{{url('admin/edit_near_miss/'.$near_miss[0]->website_id.'/'.$near_miss[0]->id)}}" class="btn btn-default"> <i class="fa fa-edit"></i> Edit</a>
             <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body details_modal_body">
             <table class="table table-bordered table-striped table-hover">
              
              <tr>
                  <th>DateTime</th>
                  <th>{{date("F j, Y, h:i A",strtotime($near_miss[0]->created_at))}}</th>
              </tr>
              <tr>
                  <th>Person Involved</th>
                  <th>{{ucfirst($near_miss[0]->person_involved)}}</th>
              </tr>
              <tr>
                  <th>Initials</th>
                  <th>{{ucfirst($near_miss[0]->initials)}}</th>
              </tr>
              <tr>
                  <th>Missed Tablet</th>
                  <th>@if($near_miss[0]->missed_tablet!=NULL) true @else false @endif</th>
              </tr>
              <tr>
                  <th>Extra Tablet</th>
                  <th>@if($near_miss[0]->extra_tablet!=NULL) true @else false @endif</th>
              </tr>
              <tr>
                  <th>Wrong Tablet</th>
                  <th>@if($near_miss[0]->wrong_tablet!=NULL) true @else false @endif</th>
              </tr>
              <tr>
                  <th>Wrong Day</th>
                  <th>@if($near_miss[0]->wrong_day!=NULL) true @else false @endif</th>
              </tr>
              <tr>
                  <th>Missed Tablet</th>
                  <th>@if($near_miss[0]->missed_tablet!=NULL) 1 @else 0 @endif</th>
              </tr>
              <tr>
                  <th>Extra Tablet</th>
                  <th>@if($near_miss[0]->extra_tablet!=NULL) 1 @else 0 @endif</th>
              </tr>
              <tr>
                  <th>Wrong Tablet</th>
                  <th>@if($near_miss[0]->wrong_tablet!=NULL) 1 @else 0 @endif</th>
              </tr>
              <tr>
                  <th>Wrong Day</th>
                  <th>@if($near_miss[0]->wrong_day!=NULL) 1 @else 0 @endif</th>
              </tr>
              <tr>
                  <th>Month</th>
                  <th></th>
              </tr>
              <tr>
                  <th>Others?</th>
                  <th>@if($near_miss[0]->other!=NULL){{$near_miss[0]->other}} @endif</th>
              </tr>
             <table>
          </div>
    @break
    @case('audit_info')
          <div class="modal-header">
            <span class="modal-title"></span>
             <a href="{{url('admin/edit_audit/'.$audit[0]->website_id.'/'.$audit[0]->id)}}" class="btn btn-default"> <i class="fa fa-edit"></i> Edit</a>
             <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body details_modal_body">
             <table class="table table-bordered table-striped table-hover">
              
              <tr>
                  <th>DateTime</th>
                  <th>{{date("F j, Y, h:i A",strtotime($audit[0]->created_at))}}</th>
              </tr>
              <tr>
                  <th>Paient Name</th>
                  <th>{{$audit[0]->patients->first_name.' '.$audit[0]->patients->last_name}}</th>
              </tr>
              <tr>
                  <th>Number of Weeks</th>
                  <th>{{$audit[0]->no_of_weeks}}</th>
              </tr>
              <tr>
                  <th>Store</th>
                  <th>{{strtoupper($audit[0]->facility->name)}}</th>
              </tr>
              <tr>
                  <th>Staff Initials</th>
                  <th>{{$audit[0]->staff_initials}}</th>
              </tr>
              
              
             <table>
          </div>
    @break
    @case('checking_info')
          <div class="modal-header">
            <span class="modal-title"></span>
             <a href="{{url('admin/edit_checking/'.$checking[0]->website_id.'/'.$checking[0]->id)}}" class="btn btn-default"> <i class="fa fa-edit"></i> Edit</a>
             <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body details_modal_body">
             <table class="table table-bordered table-striped table-hover">
              
              <tr>
                  <th>DateTime</th>
                  <th>{{date("F j, Y, h:i A",strtotime($checking[0]->created_at))}}</th>
              </tr>
              <tr>
                  <th>Paient Name</th>
                  <th>{{$checking[0]->patients->first_name.' '.$checking[0]->patients->last_name}}</th>
              </tr>
              <tr>
                  <th>Number of Weeks</th>
                  <th>{{$checking[0]->no_of_weeks}}</th>
              </tr>
              <tr>
                  <th>Pharmacist Sigature</th>
                  <th><img src="{{$checking[0]->pharmacist_signature}}" style="height:30px; width:80px;"></th>
              </tr>
              <tr>
                  <th>Note For Patients</th>
                  <th>{{$checking[0]->note_from_patient}}</th>
              </tr>
              <tr>
                  <th>Facility</th>
                  <th>{{strtoupper($checking[0]->patients->facility->name)}}</th>
              </tr>
              <tr>
                  <th>DD</th>
                  <th>@if($checking[0]->dd!=NULL) true @else false @endif</th>
              </tr>
              <tr>
                  <th>Location</th>
                  <th>{{$checking[0]->location}}</th>
              </tr>
              
              
             <table>
          </div>
    @break
    @case('noteForPatients_info')
          <div class="modal-header">
            <span class="modal-title"></span>
             <a href="{{url('admin/edit_note_for_patient/'.$noteForPatients[0]->website_id.'/'.$noteForPatients[0]->id)}}" class="btn btn-default"> <i class="fa fa-edit"></i> Edit</a>
             <button class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body details_modal_body">
             <table class="table table-bordered table-striped table-hover">
              
              <tr>
                  <th>Note-Date</th>
                  <th>{{date("F j, Y, h:i A",strtotime($noteForPatients[0]->created_at))}}</th>
              </tr>
              <tr>
                  <th>Paient Name</th>
                  <th>{{$noteForPatients[0]->patients->first_name.' '.$noteForPatients[0]->patients->last_name}}</th>
              </tr>
              <tr>
                  <th>Note For Patients</th>
                  <th>{{$noteForPatients[0]->notes_for_patients}}</th>
              </tr>
              <tr>
                  <th>Dob</th>
                  <th>{{date("F j, Y",strtotime($noteForPatients[0]->dob))}}</th>
              </tr>
              <tr>
                  <th>Send the note as a text  message</th>
                  <th>@if($noteForPatients[0]->notes_as_text!=NULL) true @else false @endif</th>
              </tr>
             
              
              
             <table>
          </div>
    @break
    @default
    <span>Something went wrong, please try again</span>
@endswitch

