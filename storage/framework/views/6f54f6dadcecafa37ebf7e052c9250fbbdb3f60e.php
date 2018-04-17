<?php $__env->startSection('content'); ?>

 <div align="" style="margin-left: 12px">
      
         <div class="md-card" >
             <div   class="uk-grid" data-uk-grid-margin>
               <div class="uk-grid-1-1 uk-container-center">
                     <?php $sys = app('App\Http\Controllers\SystemController'); ?>
                  <?php for ($i = 1; $i <= 1; $i++) {?>

  <table   border="0">
        <tr>
          <td   style="border:dashed; text-align: left;"><table width="738" height="451" border="0" cellspacing="1">
            <tr>
              <td colspan="4">
                  <table   border="0">
                    <tr>
                      <td width="10">&nbsp;</td>
                      <td width="722"><div align="center" >
                        <div  class=" uk-margin-bottom-remove" >
                            
                            <img src='<?php echo e(url("public/assets/img/logo.png")); ?>' style="width:100px;height: auto"/>
                            <h3>Takoradi Technical University - Finance Office</h3></div>
                        <span class="uk-text-bold uk-margin-top-remove"><?php echo $transaction->FEE_TYPE; ?>  Receipt
                          </span>
                              <P></P>
                              <span class="uk-text-bold">Total Academic year fees GHC<?php echo @$student->BILLS; ?></span>
                         
                      </div>
                      <div align="center"></div></td>
                    </tr>
                    </table>
              </td>
            </tr>
            <tr>
              <td colspan="4"><table width="769" border="0">
                <tr>
                  <td><table width="758" border="0">
                    <tr>
                      <td width="103"><div align="right"><strong>
                                                Date:</strong></div></td>
                      <td width="281" >  <?php echo date('D, d/m/Y, g:i a',strtotime(@$transaction->TRANSDATE)); ?>&nbsp;</td>
                      <td width="172"><div align="right"><strong>Receipt No.</strong></div></td>
                      <td width="184" ><?php echo @$transaction->RECEIPTNO;; ?>&nbsp;</td>
                      </tr>
                    <tr>
                        <td align="right"><strong>Programme:</strong></td>
                      <td><?php echo @$sys->getProgram(@$student->PROGRAMMECODE ); ?></td>
                      <td><div align="right"><strong>Level:</strong></div></td>
                      <td ><?php echo @$student->levels->slug; ?></td>
                      </tr>
                  </table></td>
                </tr>
              </table></td>
              </tr>
               <tr>
              <td width="164"><strong>Index Number:</strong></td>
              <td width="602" colspan="3" style=" border-bottom-style:dotted"><strong><?php echo @$student->INDEXNO; ?></strong></td>
            </tr>
            <tr>
              <td width="164"><strong>Name:</strong></td>
              <td width="602" colspan="3" style=" border-bottom-style:dotted"><strong><?php echo @$student->NAME; ?></strong></td>
            </tr>
            <tr>
            </tr>
            <?php if(@$transaction->bank->NAME!=""): ?>
            <tr>
              <td><strong>Bank Paid to:</strong></td>
              <td colspan="3" style=" border-bottom-style:dotted"><strong><?php echo strtoupper(@$transaction->bank->NAME); ?></strong></td>
            </tr>
            <?php endif; ?>
             
            <tr>
              <td><strong>Amount Paid:</strong></td>
              <td colspan="3" style=" border-bottom-style:dotted"><strong>GHC<?php echo @$transaction->AMOUNT; ?>.00</strong>&nbsp;(<span > <?php echo $words; ?></span> )</td>
            </tr>
             <tr>
              <td><strong>Balance:</strong></td>
              <td colspan="3" style=" border-bottom-style:dotted"><strong>GHC<?php echo @$student->BILL_OWING; ?>.00</strong>&nbsp;</td>
            </tr>
            <tr>
                
           
            <tr>
                
            </tr> 
            <tr>
              <td colspan="4" align="center">&nbsp;
                <div style="width:90%">Goto records.ttuportal.com to do your semester registration thanks</div></td>
            </tr>
            <tr>
                 <td colspan="4" align="center">&nbsp;
                     <div style="width:90%"> Your Username is <b><?php echo @$student->INDEXNO; ?></b> And your Password is <b><?php echo @$sys->getStudentPassword(@$student->INDEXNO); ?></b></div></td>
         
            </tr>
            <tr>
                <td colspan="4" align="center">&nbsp;
                     <center> <div class="visible-print text-center" align='center'>
                                 <?php echo QrCode::size(100)->generate(Request::url());; ?> 

                                </div>
                               </center>
                </td>
            </tr><?php  \Session::forget('students');?>
          </table></td>
        </tr>
      </table>
                    

 <?php }
?>

                 
                </div>

         </div>
     </div>
 
 </div>
  
        
 <?php $__env->stopSection(); ?>
 
<?php $__env->startSection('js'); ?>
 <script type="text/javascript">
  
$(document).ready(function(){
window.print();
//window.close();
});

</script>
  
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.printlayout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>