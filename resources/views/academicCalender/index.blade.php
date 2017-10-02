@extends('layouts.app')

 
@section('style')
 
@endsection
 @section('content')
   <div class="md-card-content">
@if(Session::has('success'))
            <div style="text-align: center" class="uk-alert uk-alert-success" data-uk-alert="">
                {!! Session::get('success') !!}
            </div>
 @endif
 @if(Session::has('error'))
            <div style="text-align: center" class="uk-alert uk-alert-danger" data-uk-alert="">
                {!! Session::get('error') !!}
            </div>
 @endif
 
     @if (count($errors) > 0)

    <div class="uk-form-row">
        <div class="uk-alert uk-alert-danger" style="background-color: red;color: white">

              <ul>
                @foreach ($errors->all() as $error)
                  <li> {{  $error  }} </li>
                @endforeach
          </ul>
    </div>
  </div>
@endif
  </div>
 
 <h5>Academic Calender</h5>
 <div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">
     <div class="uk-overflow-container" id='print'>
         <center><span class="uk-text-success uk-text-bold">{!! $data->total()!!} Records</span></center>
                <table class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter"> 
            <thead>
                 <tr>
                <th class="filter-false remove sorter-false" data-priority="6">NO</th>
                <th>Academic Year</th>
                <th>Semester</th>
                <th>Online Registration Status</th>
                <th>Entering of Mark Status</th>
                 <th  class="filter-false remove sorter-false uk-text-center" colspan="2" data-priority="1">ACTION</th>   
                     
                </tr>
             </thead>
             <tbody>

                 @foreach($data as $index=>$row) 
 
                 <tr align="">
                     <td  class="uk-width-2-10"> {{ $data->perPage()*($data->currentPage()-1)+($index+1) }} </td>
                     <td class="uk-text-primary uk-text-bold"> {{ @$row->YEAR }}</td>
                     <td class="uk-text-primary uk-text-bold  "> {{ @$row->SEMESTER}}</td>
                        </td>
                     <td class="uk-text-center">
                         @if($row->STATUS==1)<span class="uk-badge uk-badge-success">Opened</span>
                         <span> <a href='{{url("fireCalender/$row->ID/id/closeReg/action")}}' ><i title='Click to close online registration' onclick="return confirm('Are you sure you want to close online registration?' );" class="md-icon material-icons uk-text-danger">edit</i></span>
                       @else <span class="uk-badge uk-badge-danger">Closed</span>&nbsp;&nbsp;<span> <a href='{{url("fireCalender/$row->ID/id/openReg/action")}}' ><i title='Click to open online registration' onclick="return confirm('Are you sure you want to open online registration?' );" class="md-icon material-icons uk-text-success">edit</i></span> @endif</td>
                     
                 
                <td class="uk-text-center">@if($row->ENTER_RESULT==1)<span class="uk-badge uk-badge-success">Opened</span><span> <a href='{{url("fireCalender/$row->ID/id/closeMark/action")}}' ><i title='Click to close entering of marks' onclick="return confirm('Are you sure you want to close entering of marks?' );" class="md-icon material-icons uk-text-danger">edit</i></span> @else <span class="uk-badge uk-badge-danger">Closed</span>&nbsp;&nbsp;<span> <a href='{{url("fireCalender/$row->ID/id/openMark/action")}}' ><i onclick="return confirm('Are you sure you want to open entering of marks?' );" title='Click to open online registration'  class="md-icon material-icons uk-text-success">edit</i></span> @endif</td>
                <td class="uk-text-center">
                    @if($row->ENTER_RESULT==0 &&$row->STATUS==0)
                      {!!Form::open(['action' => ['AcademicCalenderController@destroy', 'id'=>$row->ID], 'method' => 'DELETE','name'=>'myform' ,'style' => 'display: inline;'])  !!}

                                                   <i onclick="UIkit.modal.confirm('Are you sure you want to delete this item?', function(){ document.forms[0].submit(); });" title="click to delete calender" class="sidebar-menu-icon material-icons md-18 uk-text-danger">delete</i>
                                                        <input type='hidden' name='item' value='{{$row->ID}}'/>  
                                                     {!! Form::close() !!}
                    @else
                    <span class="uk-badge uk-badge-success">Item in use</span>
                    @endif
                </td>
                 </tr>
                 @endforeach
             </tbody>
                                    
                             </table>
           {!! (new Landish\Pagination\UIKit($data->appends(old())))->render() !!}
         </table>
     </div>
<div class="md-fab-wrapper">
        <a class="md-fab md-fab-small md-fab-accent md-fab-wave" href="{!!url('/create_calender')!!}" >
            <i class="material-icons md-18">&#xE145;</i>
        </a>
    </div>
 </div>
    </div>
 </div>
@endsection
@section('js')
 
 
@endsection