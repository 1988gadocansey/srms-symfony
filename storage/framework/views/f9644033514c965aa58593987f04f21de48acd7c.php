<?php $__env->startSection('style'); ?>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <?php $sys = app('App\Http\Controllers\SystemController'); ?>

    <div class="md-card-content">

        <div style="text-align: center;display: none" class="uk-alert uk-alert-success" data-uk-alert="">



        </div>







        <div style="text-align: center;display: none" class="uk-alert uk-alert-danger" data-uk-alert="">



        </div>



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



    <div style="">

        <div class="uk-margin-bottom" style="margin-left:900px" >





            <a href="#" class="md-btn md-btn-small md-btn-success uk-margin-right" id="printTable">Print Table</a>

            <!--  <a href="#" class="md-btn md-btn-small md-btn-success uk-margin-right" id="">Import from Excel</a>

             -->

            <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">

                <button class="md-btn md-btn-small md-btn-success"> columns <i class="uk-icon-caret-down"></i></button>

                <div class="uk-dropdown">

                    <ul class="uk-nav uk-nav-dropdown" id="columnSelector"></ul>

                </div>

            </div>











            <div style="margin-top: -5px" class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">

                <button class="md-btn md-btn-small md-btn-success uk-margin-small-top">Export <i class="uk-icon-caret-down"></i></button>

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









            <i title="click to print" onclick="javascript:printDiv('print')" class="material-icons md-36 uk-text-success"   >print</i>





            <a href="<?php echo e(url('/report/broadsheet')); ?>" ><i   title="refresh this page" class="uk-icon-refresh uk-icon-medium "></i></a>







        </div>

    </div>



    <div class="uk-width-xLarge-1-1">

        <div class="md-card">

            <div class="md-card-content">



                <form action="<?php echo e(url('/process_broadsheet')); ?>"  method="POST" accept-charset="utf-8"  >

                    <?php echo csrf_field(); ?>


                    <div class="uk-grid" data-uk-grid-margin="">



                        <div class="uk-width-medium-1-5">

                            <div class="uk-margin-small-top">

                                <?php echo Form::select('program',

                            ($program ),

                              old("program",""),

                                ['class' => 'md-input parents','id'=>"parents",'required'=>'','placeholder'=>'select program']  ); ?>


                            </div>

                        </div>

                        <div class="uk-width-medium-1-5">

                            <div class="uk-margin-small-top">

                                <?php echo Form::select('level',

                            ( $level ),

                              old("level",""),

                                ['class' => 'md-input parents','required'=>'','id'=>"parents",'placeholder'=>'select level'] ); ?>


                            </div>

                        </div>



                        <div class="uk-width-medium-1-5">

                            <div class="uk-margin-small-top">



                                <?php echo Form::select('semester', array('1'=>'1st sem','2'=>'2nd sem','3' => '3rd sem'), null, ['placeholder' => 'select semester','id'=>'parents','class'=>'md-input parents','required'=>''],old("semester",""));; ?>




                            </div>

                        </div>



                        <div class="uk-width-medium-1-5">

                            <div class="uk-margin-small-top">

                                <?php echo Form::select('year',

                          (['' => 'Select year'] +$year ),

                            old("year",""),

                              ['class' => 'md-input parenst','id'=>"parents" ,'required'=>''] ); ?>   </div>

                        </div>



                        <!--                         <div class="uk-width-medium-1-5">

                                                    <div class="uk-margin-small-top">

                                                        <input type="text" style=" "   name="search"  class="md-input" placeholder="search by course name or course code">

                                                    </div>

                                                </div>-->















                    </div>

                    <div  align='center'>



                        <button class="md-btn  md-btn-small md-btn-success uk-margin-small-top" type="submit"><i class="material-icons">search</i></button>



                    </div>



                </form>

            </div>

        </div>

    </div>



    <?php if(Request::isMethod('post')): ?>
                            <p></p>

                            <h4 class="heading_c"><center>Broadsheet for Academic Board, <?php echo e($years); ?>, Semester <?php echo e($term); ?> <br/><br/><?php echo e($sys->getProgram($programs)); ?>,   Level <?php echo e($levels); ?></center></h4>

                            <p></p>


        <div class="uk-width-xLarge-1-1">

            <div class="md-card">

                <div class="md-card-content">

                    <div class="uk-overflow-container" id='print'>


                        <table border='1' class="uk-table uk-table-hover uk-table-align-vertical uk-table-nowrap tablesorter tablesorter-altair" id="ts_pager_filter">
                            
                            <thead>
                               
                            <tr>

                                <th class="filter-false remove sorter-false"  >NO</th>

                                <th>INDEX</th>

                                <th>STUDENT</th>

                                <?php



                                $count=0;

                                $mark=array();

                                ?>

                                <?php foreach($headers as $header=> $td): ?>



                                    <th> <?php echo e(strtoupper(@$td['code'])); ?></th>



                                <?php endforeach; ?>

                                <th> GPA</th>

                                <th> CGPA</th>

                            </tr>







                            </thead>

                            <tbody>










                            <?php
                            $totalCount=0;

                            $grades= array();
                            $courseCode=array();
                            $gradeArray=array("A+","A","B+","B","C+","C","D+","D","F");
                            $countGrade=array();
                            ?>
                            <?php foreach($student as $stud=> $pupil): ?>  <?php  $count++;?>
                            

                            <?php if($pupil->grade!="E"): ?>

                                <tr>



                                    <td><?php  $students[]=$pupil->indexno;

                                        \Session::put('students', $students);echo $count?></td>

                                    <td><?php echo $pupil->indexno;?></td>

                                    <td> <?php echo e(strtoupper(@$pupil->student->NAME)); ?></td>



                                    <?php

                                    $a=$pupil->student->INDEXNO;



                                    for($i=0;$i<count($course);$i++){


                                            $gradeObject=$sys->getCourseGradeNoticeBoard($course[$i],$years,$term,$a,$pupil->level);
                                        print_r("<td>".  @round(@$gradeObject->total). "&nbsp;&nbsp;  - &nbsp;&nbsp; " .@$gradeObject->grade."</td>");


                                    }

                                    ?>



                                     <td><?php echo e($sys->getGPABySem(@$a,$term,$pupil->level)); ?></td>

                                    <td><?php echo e($sys->getCGPA(@$a)); ?></td>

                                </tr>




                            <?php endif; ?>



                            <?php endforeach; ?>


                            <tr><td colspan="<?php echo count($course) + 5; ?>" align="center">Grades Count</td></tr>

                            <?php foreach($gradeArray as  $col): ?>

                                <tr>
                                    <td></td><td></td>  <td><?php echo e($col); ?> </td>

                                    <?php foreach($course as  $item=>$needle): ?>
                                        <td>


                                            <?php echo e(@$sys->getCourseGradeCounter($needle,$term,$levels,$years,$programs,$col)); ?>






                                        </td>
                                    <?php endforeach; ?>


                                </tr>


                            <?php endforeach; ?>



                            <tr>
                                <td></td>
                                <td></td>
                                <td>Total</td>
                                <?php foreach($course as  $item=>$needle): ?>
                                    <td>


                                    <?php echo $sys->getCourseGradeCounterTotal($needle,$term,$levels,$years,$programs,$gradeArray);?>

                                    </td>
                                <?php endforeach; ?>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>CPA</td>
                                <?php foreach($course as  $key=>$val): ?>

                                    <td><?php echo e(@$sys->getLecturerAverage(@$sys->getCourseGradeArray($val,$term,$levels,$years,$programs))); ?> </td>


                                <?php endforeach; ?>

                            </tr>
                            <tr><td colspan="<?php echo count($course) + 5; ?>" align="center">Courses</td></tr>

                            <tr>
                            <td>NO</td>
                            <td>CODE</td>
                            <td>COURSE NAME</td>
                            

                            <?php $n=0;?>
                            <?php foreach($course as  $key): ?>
                                <?php $n++; $courseDetail=$sys->getCourseByCodeObject($key)?>
                                <tr>
                                    <td><?php echo e($n); ?></td>

                                    <td><?php echo e($key); ?> </td>
                                    <td><?php echo e(strtoupper(@$courseDetail[0]->COURSE_NAME)); ?> </td>

                                </tr>
                            <?php endforeach; ?>


                            </tbody>


                        </table>



                    










                    </div>

                </div>



            </div>

        </div>

    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>



    <script type="text/javascript">







    </script>

    <script src="<?php echo url('public/assets/js/select2.full.min.js'); ?>"></script>

    <script>

        $(document).ready(function () {

            $('select').select2({width: "resolve"});

        });</script>







<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>