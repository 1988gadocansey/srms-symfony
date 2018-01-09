<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentModel;
use App\Models\ApplicantModel;
use App\Models;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Response;

class APIController extends Controller {

    /**
     * Create a new controller instance.
     *

     * @return void
     */
    public function __construct() {
        // $this->middleware('auth');
    }
      public function getStudentData(Request $request) {
        header('Content-Type: application/json');
        $indexno=$request->input("student");
        $data=@ApplicantModel::where("APPLICATION_NUMBER",$indexno)->orWhere("NAME","LIKE","%$indexno%")->first();
        return response()->json(array('data'=>$data));
         
      }
       public function getStudentProgram(Request $request,$program) {
        header('Content-Type: application/json');
        $indexno=$request->input("student");
        $data=@Models\StudentModel::where("PROGRAMMECODE",$program)->get();
        return response()->json(array('data'=>$data));
         
      }

      public function getStudentHall(Request $request) {
        header('Content-Type: application/json');
        $indexno=$request->input("student");
        $data=@ApplicantModel::where("APPLICATION_NUMBER",$indexno)->first();
        if(!empty($data)){
        return $data->HALL_ADMITTED;
        }
        else{
            return "Non Resident";
        }
         
      }
    

    public function qualityAssurance(Request $request, $indexno) {
        @StudentModel::where("INDEXNO", $indexno)->update(array("QUALITY_ASSURANCE" => 1));
        // return $this->response->json("status","Student Lecturer Assessment received at main system");
        return Response::json("Student Lecturer Assessment received at main system", "200");
    }

    public function liaison(Request $request, $indexno) {
        @ StudentModel::where("INDEXNO", $indexno)->update(array("LIAISON" => 1));
        return Response::json("Student Liaison forms received at main system", "200");
    }

    public function getReceipt() {
        \DB::beginTransaction();
        try {
            $receiptno_query = Models\ReceiptModel::first();
            $receiptno = date('Y') . str_pad($receiptno_query->no, 5, "0", STR_PAD_LEFT);
            \DB::commit();
            return $receiptno;
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function updateReceipt() {
        \DB::beginTransaction();
        try {
            $query = Models\ReceiptModel::first();

            $result = $query->increment("no");
            if ($result) {
                \DB::commit();
            }
        } catch (\Exception $e) {
            \DB::rollback();
        }
    }

    public function payFee(Request $request, SystemController $sys) {
         header('Content-Type: application/json');
        $indexno=$request->input("accountRef");
        $amount=$request->input("amount");
        $bank=$request->input("accountNumber");
        $type=$request->input("auxRef4");
        $transactionId=$request->input("transactionId");
        $date=$request->input("transactionDate");
        $array = $sys->getSemYear();
        $sem = $array[0]->SEMESTER;
        $year = $array[0]->YEAR;

        $data = @StudentModel::where("INDEXNO", $indexno)->orWhere("STNO", $indexno)->first();
        // return Response::json("Student Liaison forms received at main system","200");
        
//        if (@$data->NAME!="") {
//if ( $data->LEVEL  == '100H') {
//            $level = "200H";
//            $years = '200H';
//        } elseif ($data->LEVEL  == '200H') {
//            $level = "300H";
//            $years = '300H';
//         } elseif ($data->LEVEL  == '300H') {
//            $level = "Alumni";
//            $years = 'Alumni';
//            $status=$level;
//        }
//         elseif ($data->LEVEL  == '100NT') {
//              $level = "200NT";
//            $years = '200NT';
//         }
//         elseif ($data->LEVEL  == '200NT') {
//             $level = "Alumni";
//            $years = 'Alumni';
//            $status=$level;
//         }
//          elseif ($data->LEVEL  == '100BTT') {
//             $level = "200BTT";
//            $years = '200BTT';
//            $status=$level;
//         }else{
//              $level = "Alumni";
//            $years = 'Alumni';
//            $status=$level;
//         }
         
            $bankDetail = @Models\BankModel::where("ACCOUNT_NUMBER", $bank)->first();

            if(!empty($data)){
                $bill = $sys->getYearBill($year, $data->LEVEL, $data->PROGRAMMECODE);
                $billOwing = $data->BILL_OWING + $bill;
                $owing = $billOwing - $amount;
                if ($billOwing <= $amount) {
                    $details = "Full payment";

                    
                } else {
                    $details = "Part payment";
                }
                $que = Models\PortalPasswordModel::where("username", $indexno)->first();
                    if (empty($que)&& !empty($indexno)) {
                        $program = $data->PROGRAMMECODE;
                        $str = 'abcdefhkmnprtuvwxy34678abcdefhkmnprtuvwxy34678';
                        $shuffled = str_shuffle($str);
                        $vcode = substr($shuffled, 0, 9);
                        $real = strtoupper($vcode);
                        $level = $data->LEVEL;
                        Models\PortalPasswordModel::create([
                            'username' => $indexno,
                            'real_password' => $real,
                            'level' => $level,
                            'programme' => $program,
                            'biodata_update' => '1',
                            'password' => bcrypt($real),
                        ]);
                        $phone=$data->TELEPHONENO;
                        $fname=$data->FIRSTNAME;
                        
                                 $message = "Online credential: visit records.ttuportal.com with $indexno as your username  and $real as password and follow the course registration steps.";
              
                    
                   // @$sys->firesms($message, $phone, $indexno);
                        
                    
                    }
    
                $receipt = $this->getReceipt();

                $feeLedger = new Models\FeePaymentModel();
                $feeLedger->INDEXNO = $indexno;
                $feeLedger->PROGRAMME = $data->PROGRAMMECODE;
                $feeLedger->AMOUNT = $amount;
                $feeLedger->PAYMENTTYPE = $type;
                $feeLedger->PAYMENTDETAILS = $details . " of " . $type;
                $feeLedger->BANK_DATE = $date;

                $feeLedger->LEVEL = $data->LEVEL;
                $feeLedger->RECIEPIENT = 751999;
                $feeLedger->BANK = $bank;
                $feeLedger->TRANSACTION_ID = $transactionId;
                $feeLedger->RECEIPTNO = $receipt;
                $feeLedger->YEAR = $year;
                $feeLedger->FEE_TYPE = $type;
                $feeLedger->SEMESTER = $sem;
                if ($feeLedger->save()) {
                    @StudentModel::where("INDEXNO", $indexno)->orWhere("STNO", $indexno)->update(array("BILL_OWING" => $owing, "BILLS" => $bill ));
                    @$this->updateReceipt();
                    //return Response::json("Success", "01");
 header('Content-Type: application/json');
                  // return  json_encode(array('responseCode'=>'01','responseMessage'=>'Successfully Processed'));
                 return response()->json(array('responseCode'=>'01','responseMessage'=>'Successfully Processed'));
         
                   } else {
                     header('Content-Type: application/json');
                    // return  json_encode(array('responseCode'=>'09','responseMessage'=>'Failed'));
            return response()->json(array('responseCode'=>'09','responseMessage'=>'Failed'));
                }
            
        } 
        
            $data = @Models\ApplicantModel::where("APPLICATION_NUMBER", $indexno)->first();
           if(!empty($data)){
            
            if (strpos($data->PROGRAMME_ADMITTED, "H") === 0) {
            $level = "100H";
            $years = '100H';
        } elseif (strpos($data->PROGRAMME_ADMITTED, "B") === 0) {
            $level = "200BTT";
            $years = '200BTT';
        } else {
            $level = "100NT";
            $years = '100NT';
        }
            $bankDetail = @Models\BankModel::where("ACCOUNT_NUMBER", $bank)->first();
             
            if (@$bankDetail) {
                $bill = $sys->getYearBill($year, $level, $data->PROGRAMME_ADMITTED);
                $billOwing = $data->BILL_OWING + $bill;
                $owing = $billOwing - $amount;
                if ($billOwing <= $amount) {
                    $details = "Full payment";
                   
                } else {
                    $details = "Part payment";
                }
                 $sql = StudentModel::where("STNO", $indexno)->first();
                    if (empty($sql)) {
                        /////////////////////////////////////////////////////


                        $query = new StudentModel();
                        $query->YEAR = $year;
                        $query->LEVEL = $level;
                        $query->FIRSTNAME = $data->FIRSTNAME;
                        $query->SURNAME = $data->SURNAME;
                        $query->OTHERNAMES = $data->OTHERNAME;
                        $query->TITLE = $data->TITLE;
                        $query->SEX = $data->GENDER;
                        $query->DATEOFBIRTH = $data->DOB;
                        $query->NAME = $data->NAME;
                        $query->AGE = $data->AGE;

                        $query->MARITAL_STATUS = $data->MARITAL_STATUS;
                        $query->HALL = $data->HALL_ADMITTED;
                        $query->ADDRESS = $data->ADDRESS;
                        $query->RESIDENTIAL_ADDRESS = $data->RESIDENTIAL_ADDRESS;
                        $query->EMAIL = $data->EMAIL;
                        $query->PROGRAMMECODE = $data->PROGRAMME_ADMITTED;
                        $query->TELEPHONENO = $data->PHONE;
                        $query->COUNTRY = $data->NATIONALITY;
                        $query->REGION = $data->REGION;
                        $query->RELIGION = $data->RELIGION;
                        $query->HOMETOWN = $data->HOMETOWN;
                        $query->GUARDIAN_NAME = $data->GURDIAN_NAME;
                        $query->GUARDIAN_ADDRESS = $data->GURDIAN_ADDRESS;
                        $query->GUARDIAN_PHONE = $data->GURDIAN_PHONE;
                        $query->GUARDIAN_OCCUPATION = $data->GURDIAN_OCCUPATION;
                        $query->DISABILITY = $data->PHYSICALLY_DISABLED;
                        $query->STATUS = "In School";
                        $query->SYSUPDATE = "1";


                        //$query->BILLS=$sys->getYearBill( $fiscalYear, $level, $program);
                        // $query->BILL_OWING=$sys->getYearBill( $fiscalYear, $level, $program);
                        $query->STNO = $data->APPLICATION_NUMBER;
                        $query->INDEXNO = $data->APPLICATION_NUMBER;
                        $query->save();
                    }



                     $que = Models\PortalPasswordModel::where("username", $indexno)->first();
                    if (empty($que)) {
                        $program = $data->PROGRAMME_ADMITTED;
                        $str = 'abcdefhkmnprtuvwxyz234678';
                        $shuffled = str_shuffle($str);
                        $vcode = substr($shuffled, 0, 9);
                        $real = strtoupper($vcode);
                        $level = $level;
                        Models\PortalPasswordModel::create([
                             'username' => $indexno,
                            'real_password' => $real,
                             'level' => $level,
                            'programme' => $program,
                            'biodata_update' => '1',
                            'password' => bcrypt($real),
                      ]);

                    }
                     $phone=$data->PHONE;
                        $fname=$data->FIRSTNAME;
                $receipt = $this->getReceipt();

                $feeLedger = new Models\FeePaymentModel();
                $feeLedger->INDEXNO = $indexno;
                $feeLedger->PROGRAMME = $data->PROGRAMME_ADMITTED;
                $feeLedger->AMOUNT = $amount;
                $feeLedger->PAYMENTTYPE = $type;
                $feeLedger->PAYMENTDETAILS = $details . " of " . $type;
                $feeLedger->BANK_DATE = $date;

                $feeLedger->LEVEL = $level;
                $feeLedger->RECIEPIENT = 751999;
                $feeLedger->BANK = $bank;
                $feeLedger->TRANSACTION_ID = $transactionId;
                $feeLedger->RECEIPTNO = $receipt;
                $feeLedger->YEAR = $year;
                $feeLedger->FEE_TYPE = $type;
                $feeLedger->SEMESTER = $sem;
                
                if ($feeLedger->save()) {
                    @StudentModel::where("INDEXNO", $indexno)->orWhere("STNO", $indexno)->update(array("BILL_OWING" => $owing, "BILLS" => $bill));
                    @$this->updateReceipt();
                    
                        
                        $message = "Online credential: visit records.ttuportal.com with $indexno as your username  and $real as password and follow the course registration steps.";
                   
                    
                   // @$sys->firesms($message, $phone, $indexno);
                    header('Content-Type: application/json');
                  // return  json_encode(array('responseCode'=>'01','responseMessage'=>'Successfully Processed'));
                 return response()->json(array('responseCode'=>'01','responseMessage'=>'Successfully Processed'));
 
                } else {
                   header('Content-Type: application/json');
                  // return  json_encode(array('responseCode'=>'01','responseMessage'=>'Successfully Processed'));
                 return response()->json(array('responseCode'=>'09','responseMessage'=>'Failed'));
 
                }
            }
             
        }
        else{
             $receipt = $this->getReceipt();

              $feeLedger = new Models\FeePaymentModel();
                $feeLedger->INDEXNO = $indexno;
                $feeLedger->PROGRAMME = "VOUCHER";
                $feeLedger->AMOUNT = $amount;
                $feeLedger->PAYMENTTYPE = $type;
                $feeLedger->PAYMENTDETAILS ="Sale of forms";
                $feeLedger->BANK_DATE = $date;

                $feeLedger->LEVEL = "100H";
                $feeLedger->RECIEPIENT = 751999;
                $feeLedger->BANK = $bank;
                $feeLedger->TRANSACTION_ID = $transactionId;
                $feeLedger->RECEIPTNO = $receipt;
                $feeLedger->YEAR = $year;
                $feeLedger->FEE_TYPE = $type;
                $feeLedger->SEMESTER = $sem;
                
                if ($feeLedger->save()) {
                      @$this->updateReceipt();
                       header('Content-Type: application/json');
                       
                  // return  json_encode(array('responseCode'=>'01','responseMessage'=>'Successfully Processed'));
                 return response()->json(array('responseCode'=>'01','responseMessage'=>'Successfully Processed'));
 
                }
                else{
                       header('Content-Type: application/json');
                  // return  json_encode(array('responseCode'=>'01','responseMessage'=>'Successfully Processed'));
                 return response()->json(array('responseCode'=>'09','responseMessage'=>'Failed'));
 
                }
            
        }
        
        
    }
    

    /**
     * Destroy the given task.
     *
     * @param  Request  $request
     * @param  Task  $task
     * @return Response
     */
    public function destroy(Request $request, Task $task) {
        $this->authorize('destroy', $task);

        $task->delete();

        return redirect('/tasks');
    }

}
