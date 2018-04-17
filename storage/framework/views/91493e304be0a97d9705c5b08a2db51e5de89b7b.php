<?php $__env->startSection('content'); ?>
    <?php $sys = app('App\Http\Controllers\SystemController'); ?>

    <div class="containers">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">


                    <div class="panel-body" id='gad'>
                        <a onclick="javascript:printDiv('print')" class="md-btn md-btn-flat md-btn-flat-primary md-btn-wave">Click
                            to print form</a>
                        <div id='print'>
                            <?php echo e($data); ?>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



<?php $__env->stopSection(); ?>
<script>
    window.print();
</script>
<?php echo $__env->make('layouts.printlayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>