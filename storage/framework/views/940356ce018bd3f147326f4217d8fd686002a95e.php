 
<?php $__env->startSection('style'); ?>
 
<?php $__env->stopSection(); ?>
 <?php $__env->startSection('content'); ?>
  
   <div class="md-card-content">
        
 <?php if($messages=Session::get("success")): ?>

    <div class="uk-form-row">
        <div style="text-align: center" class="uk-alert uk-alert-success" data-uk-alert="">

              <ul>
                <?php foreach($messages as $message): ?>
                  <li> <?php echo $message; ?> </li>
                <?php endforeach; ?>
          </ul>
    </div>
  </div>
   </div>
<?php endif; ?>
 
  
     <?php if(count($errors) > 0): ?>

    
        <div class="uk-alert uk-alert-danger  uk-alert-close" style="background-color: red;color: white" data-uk-alert="">

              <ul>
                <?php foreach($errors->all() as $error): ?>
                  <li><?php echo $error; ?> </li>
                <?php endforeach; ?>
               </ul>
        </div>
   
<?php endif; ?>
 
 
 </div>
<h5 class="heading_c uk-margin-bottom">Admitted Applicants by Programme Statistics</h5>
   
 <div style="">
     <div class="uk-margin-bottom" style="margin-left:1000px" >
         <i title="click to print" onclick="javascript:printDiv('print')" class="material-icons md-36 uk-text-success"   >print</i>
                   
          <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
             <button class="md-btn md-btn-small md-btn-success"> show/hide columns <i class="uk-icon-caret-down"></i></button>
             <div class="uk-dropdown">
                 <ul class="uk-nav uk-nav-dropdown" id="columnSelector"></ul>
             </div>
         </div>
         <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
                                <button class="md-btn md-btn-small md-btn-success uk-margin-right">Export <i class="uk-icon-caret-down"></i></button>
                                <div class="uk-dropdown">
                                    <ul class="uk-nav uk-nav-dropdown">
                                         <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'csv',escape:'false'});"><img src='<?php echo url("public/assets/icons/csv.png"); ?>' width="24"/> CSV</a></li>
                                           
                                            <li class="uk-nav-divider"></li>
                                            <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'excel',escape:'false'});"><img src='<?php echo url("public/assets/icons/xls.png"); ?>' width="24"/> XLS</a></li>
                                            <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'doc',escape:'false'});"><img src='<?php echo url("public/assets/icons/word.png"); ?>' width="24"/> Word</a></li>
                                            <li><a href="#" onClick ="$('#ts_pager_filter').tableExport({type:'powerpoint',escape:'false'});"><img src='<?php echo url("public/assets/icons/ppt.png"); ?>' width="24"/> PowerPoint</a></li>
                                            <li class="uk-nav-divider"></li>
                                           
                                    </ul>
                                </div>
                            </div>
     </div>
     
 </div>
 <!-- filters here -->
  <?php $fee = app('App\Http\Controllers\FeeController'); ?>
   <?php $sys = app('App\Http\Controllers\SystemController'); ?>
  
 <div class="uk-width-xLarge-1-1">
    <div class="md-card">
        <div class="md-card-content">

            <form action=" "  method="get" accept-charset="utf-8" novalidate id="group">
                <?php echo csrf_field(); ?>

                <div class="uk-grid" data-uk-grid-margin="">

                    <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                            <?php echo Form::select('program', 
                            (['' => 'All programs'] + $programme ), 
                            old("program",""),
                            ['class' => 'md-input parent','id'=>"parent",'placeholder'=>'select program'] ); ?>

                        </div>
                    </div>
                    
                    <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                            <?php echo Form::select('department', 
                            (['' => 'departments'] +$department  ), 
                            old("department",""),
                            ['class' => 'md-input parent','id'=>"parent"] ); ?>

                        </div>
                    </div>
                     <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                            <?php echo Form::select('school', 
                            (['' => 'by schools'] +$school  ), 
                            old("school",""),
                            ['class' => 'md-input parent','id'=>"parent"] ); ?>

                        </div>
                    </div>
<!--                    <div class="uk-width-medium-1-5">
                        <div class="uk-margin-small-top">
                            <?php echo Form::select('type', 
                            (['' => 'by programme types'] +$type  ), 
                            old("type",""),
                            ['class' => 'md-input parent','id'=>"parent"] ); ?>

                        </div>
                    </div>-->
                </div>
            </form>
        </div>
    </div>
 </div>
 <!-- end filters -->
 
 <div class="uk-width-xLarge-1-1">
 <div class="md-card">
 <div class="md-card-content">
  
 
     <div class="uk-overflow-container" id='print'>
          <center><span class="uk-text-success uk-text-bold"><?php echo $programcode->total(); ?> Records</span></center>
        
                 <table border="1" class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter"> 
                                  <thead>
                                        <tr>
                                            <th class="uk-width-1-10">NO</th>
                                            <th class="uk-width-1-10">PROGRAMS</th>
                                            <th>REGULAR</th>
                                            <th>CONDITIONAL</th>
                                            <th>MATURE</th>
                                            <th>TECHNICAL</th>                       
                                            <th>PROVISIONAL</th>                                  
                                            <th>TOTAL</th>      
                                        </tr>
                                    </thead>
                                    <tbody>
                                         
                                     <?php foreach($programcode as $index=> $row): ?> 
                                     <tr>
                                        <td> <?php echo e($programcode->perPage()*($programcode->currentPage()-1)+($index+1)); ?> </td>
                                         <td  <?php if($row->ID % 2 == 0 ): ?>class='md-bg-purple-100 uk-text-small'<?php else: ?> class='md-bg-cyan-100 uk-text-small' <?php endif; ?> ><?php echo e(strtoupper($sys->getProgram($row->PROGRAMMECODE))); ?></td>
                                         <td class='md-bg-grey-100 uk-text-small'><?php echo e($sys->getApplicantTotalPerProgramRegular($row->PROGRAMMECODE,"MALE")); ?><?php $totalReg[] = $sys->getApplicantTotalPerProgramRegular($row->PROGRAMMECODE,"MALE") ?></td>
                                         <td class='md-bg-grey-100 uk-text-small'><?php echo e($sys->getApplicantTotalPerProgramConditional($row->PROGRAMMECODE)); ?><?php $total1c[] = $sys->getApplicantTotalPerProgramConditional($row->PROGRAMMECODE) ?></td>
                                          <td class='md-bg-grey-100 uk-text-small'><?php echo e($sys->getApplicantTotalPerProgramMature($row->PROGRAMMECODE,"FEMALE")); ?><?php $total2c[] = $sys->getApplicantTotalPerProgramMature($row->PROGRAMMECODE,"FEMALE") ?></td>
                                         <td class='md-bg-grey-100 uk-text-small'><?php echo e($sys->getApplicantTotalPerProgramTechnical($row->PROGRAMMECODE,"MALE")); ?><?php $totalad[] = $sys->getApplicantTotalPerProgramTechnical($row->PROGRAMMECODE,"MALE") ?></td>
                                         <td class='md-bg-cyan-100 uk-text-small'><?php echo e($sys->getApplicantTotalPerProgramProvisional($row->PROGRAMMECODE)); ?><?php $totalp[] = $sys->getApplicantTotalPerProgramProvisional($row->PROGRAMMECODE) ?></td>                                                                                
                                          <td class='md-bg-grey-100 uk-text-small'><?php echo e($sys->getApplicantTotalPerProgram($row->PROGRAMMECODE)); ?><?php $totalrg[] = $sys->getApplicantTotalPerProgram($row->PROGRAMMECODE) ?>
                                          </td>
                                        
                                         </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                        <td ></td>
                                          <td>TOTAL</td>
                                         <td class='md-bg-grey-100 uk-text-small'>
                                         <?php echo array_sum ($totalReg); ?>
                                          </td>
                                          <td class='md-bg-grey-100 uk-text-small'>
                                         <?php echo array_sum ($total1c); ?>
                                          </td> 
                                          <td class='md-bg-grey-100 uk-text-small'>
                                         <?php echo array_sum ($total2c); ?>
                                          </td>
                                          <td class='md-bg-grey-100 uk-text-small'>
                                         <?php echo array_sum ($totalad); ?>
                                          </td>
                                          <td class='md-bg-grey-100 uk-text-small'>
                                         <?php echo array_sum ($totalp); ?>
                                          </td>
                                          <td class='md-bg-grey-100 uk-text-small'>
                                         <?php echo array_sum ($totalrg); ?>
                                          </td>
                                        </tr>
                                         
                                    </tbody>
                                    
                             </table>
          <table>
             
          </table>
        
           
     </div>
  
   
 </div>
 </div></div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
 <script type="text/javascript">
      
$(document).ready(function(){
 
$(".parent").on('change',function(e){
 
   $("#group").submit();
 
});
});

</script>
<script src="<?php echo url('public/assets/js/select2.full.min.js'); ?>"></script>
<script>
$(document).ready(function(){
  $('select').select2({ width: "resolve" });

  
});


</script>
 
 <!--  notifications functions -->
    <script src="public/assets/js/components_notifications.min.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>