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

class APIController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }
    public function pushToSRMS(Request $request, SystemController $sys)
    {

        $request = json_decode($request->all(),true, JSON_PRETTY_PRINT);
        dd($request);
        ini_set('max_execution_time', 280000);
        // $date = \Carbon::create(date('Y'));
        $student=$request;
        $program = $student->input('program');
        $ptype = $sys->getProgrammeType($program);
        if ($ptype == "NON TERTIARY") {
            $level = "100NT";
            $group=date("Y") + 2 . "/".(date("Y") + 3);
        } elseif ($ptype == "HND") {
            $level = "100H";
            $group=date("Y")+3 . "/".(date("Y") + 4);
        } elseif ($ptype == "BTECH") {
            $level = "100BTT";
            $group=date("Y") + 2 . "/".(date("Y") + 3);
        }
        elseif ($ptype == "DEGREE") {
            $level = "100BT";
            $group=date("Y") + 4 . "/".(date("Y") + 5);
        }
        else {
            $level = "500MT";
            $group=date("Y") + 2 . "/".(date("Y") + 3);
        }
        /////////////////////////////////////////////////////
        $checker=Models\StudentModel::where("STNO",$student->input('stno'))->get();
        if(count($checker)==0) {
            $query = new Models\StudentModel();
            $query->YEAR = $level;
            $query->LEVEL = $level;
            $query->FIRSTNAME = $student->input('firstname');
            $query->SURNAME = $student->input('lastname');
            $query->OTHERNAMES = $student->input('othernames');
            $query->TITLE = $student->input('title');
            $query->SEX = $student->input('gender');
            $query->DATEOFBIRTH = $student->input('dob');
            $query->NAME = $student->input('name');
            $query->AGE = $student->input('age');
            $query->MARITAL_STATUS = $student->input('marital');
            $query->DATE_ADMITTED = $student->input('date-admitted');
            $query->GRADUATING_GROUP = $group;
            $query->HAS_PASSWORD = 1;
            $query->HALL = $student->input('hall');
            $query->ADDRESS = $student->input('address');
            $query->RESIDENTIAL_ADDRESS = $student->input('address');
            $query->EMAIL = $student->input('email');
            $query->PROGRAMMECODE = $student->input('program');
            $query->TELEPHONENO = $student->input('phone');
            $query->COUNTRY = $student->input('country');
            $query->REGION = $student->input('region');
            $query->RELIGION = $student->input('religion');;
            $query->HOMETOWN = $student->input('hometown');
            $query->GUARDIAN_NAME = $student->input('guardian-name');
            $query->GUARDIAN_ADDRESS = $student->input('guardian-address');
            $query->GUARDIAN_PHONE = $student->input('guardian-phone');
            $query->GUARDIAN_OCCUPATION = $student->input('guardian-occupation');
            $query->DISABILITY = $student->input('disable');
            $query->STNO = $student->input('stno');
            $query->INDEXNO = $student->input('stno');
            $query->TYPE = $student->input('type');
            $query->STUDENT_TYPE = $student->input('resident');
            $query->ALLOW_REGISTER = 1;
            $query->STATUS = "Admitted";
            $query->SYSUPDATE = "1";
            $query->BILLS = $student->input('fees');
            $query->BILL_OWING = $student->input('fees');
            $query->PAID = 0.00;
            @$query->save();
            @$sys->getPassword($student->input('stno'));
        }
        else{
            Models\StudentModel::where("STNO",$student->input('stno'))->update(
                array("FIRSTNAME"=> $student->input('firstname'),
                    "SURNAME"=> $student->input('lastname'),
                    "OTHERNAMES"=>$student->input('othernames'),
                    "NAME"=>$student->input('name'),
                    "BILLS"=> $student->input('fees'),
                    "BILL_OWING"=> $student->input('fees'),
                    "PROGRAMMECODE"=> $student->input('program'),
                    "HALL"=> $student->input('hall'),
                    "GRADUATING_GROUP"=> $group,
                )
            );
        }
    }
    public function pushToSrms2(Request $request)
    {


        header('Content-Type: application/json');


        //return response()->json(array('data'=>"Student with index number $student does not exist."));
        //$json = json_decode(file_get_contents("http://45.33.4.164/admissions/srms/forward"), true, JSON_PRETTY_PRINT);
        $json = json_decode(file_get_contents("http://127.0.0.1:8000/admissions/srms/forward"), true, JSON_PRETTY_PRINT);


        $a[] = (array)$json;

        foreach ($a as $i) {
            /*$data["admission_number"] = $i["application_number"];
            $data["name"] = $i["name"];
            $data["programme"] = $i["programme"];
            $data["fees"] = $i["fees"];
            $data["hall"] = $i["hall"];
            $data["type"] = "Newly admited applicant";*/


            $sql = Models\StudentModel::where("STNO", $i["application_number"])->first();
            if (empty($sql)) {
                /////////////////////////////////////////////////////


                $query = new Models\StudentModel();
                $query->YEAR = "100H";
                $query->LEVEL = "100H";
                $query->FIRSTNAME = $i["firstname"];
                $query->SURNAME = $i["lastname"];
                $query->OTHERNAMES = $i["firstname"];
                $query->NAME= $i["name"];
                $query->TITLE = $i["title"];
                $query->SEX = $i["gender"];
                $query->DATEOFBIRTH = $i["dob"];

                $query->AGE = $i["age"];


                $query->HALL = $i["hall"];
                //$query->ADDRESS = $student->ADDRESS;
               // $query->RESIDENTIAL_ADDRESS = $student->RESIDENTIAL_ADDRESS;
               // $query->EMAIL = $student->EMAIL;
                $query->PROGRAMMECODE = $i["program"];
                $query->TELEPHONENO = $i["phone"];
               /* $query->COUNTRY = $student->NATIONALITY;
                $query->REGION = $student->REGION;
                $query->RELIGION = $student->RELIGION;
                $query->HOMETOWN = $student->HOMETOWN;
                $query->GUARDIAN_NAME = $student->GURDIAN_NAME;
                $query->GUARDIAN_ADDRESS = $student->GURDIAN_ADDRESS;
                $query->GUARDIAN_PHONE = $student->GURDIAN_PHONE;
                $query->GUARDIAN_OCCUPATION = $student->GURDIAN_OCCUPATION;
                $query->DISABILITY = $student->PHYSICALLY_DISABLED;
                $query->STATUS = "In School";
                $query->SYSUPDATE = "1";


                $query->BILLS = $student->ADMISSION_FEES;
                $query->BILL_OWING = $student->ADMISSION_FEES - $item->Amount;
                $query->STNO = $student->APPLICATION_NUMBER;
                $query->INDEXNO = $student->APPLICATION_NUMBER;
                $query->save();
                $this->getPassword($student->APPLICATION_NUMBER);*/
                $query->save();
            } else {
               // $owing = $student->ADMISSION_FEES - $item->Amount;
               // Models\StudentModel::where("STNO", $item->StudentID)->update(array("BILL_OWING" => $owing));
            }

        }


        //return response()->json(array('data' => $data));


    }

    // api to call student password
    public function getStudentPassword(Request $request, $indexno)
    {
        header('Content-Type: application/json');

        $record = @Models\PortalPasswordModel::where("username", $indexno)->first();
        $data="No password found";

        if (!empty($record)) {



            $data=$record->real_password;


        }

        return response()->json(array('data' => $data));

    }

    public function getStudentData(Request $request, $student)
    {
        header('Content-Type: application/json');

        $data = @Models\StudentModel::where("INDEXNO", $student)->orWhere("STNO", $student)->select("INDEXNO", "STNO", "NAME", "PROGRAMMECODE", "LEVEL", "BILLS", "STATUS")->first();
        if (empty($data)) {

            //return response()->json(array('data'=>"Student with index number $student does not exist."));
            $json = json_decode(file_get_contents("http://45.33.4.164/admissions/applicant/$student"), true, JSON_PRETTY_PRINT);

            $a[] = (array)$json;

           /* foreach ($a as $i) {
                $data["admission_number"] = $i["application_number"];
                $data["name"] = $i["name"];
                $data["programme"] = $i["programme"];
                $data["fees"] = $i["fees"];
                $data["hall"] = $i["hall"];
                $data["type"] = "Newly admited applicant";
            }*/


            foreach ($a as $i) {
                $data["INDEXNO"] = $i["application_number"];
                $data["STNO"] = $i["application_number"];
                $data["NAME"] = $i["name"];
                $data["PROGRAMMECODE"] = $i["programme"];
                $data["LEVEL"] = '100';
                $data["BILLS"] = $i["fees"];
                $data["STATUS"] = "Applicant";

            }


        }

        return response()->json(array('data' => $data));

    }

    public function fireVoucher(Request $request, SystemController $sys)
    {
        $data = Models\FormModel::where("PHONE", $request->input('phone'))->first();

        $pin = $data->serial;
        $serial = $data->PIN;
        $phone = $request->input('phone');
        $message = "Admission voucher: serial: $serial  pin code: $pin . Login at admissions.ttuportal.com Thanks";


        $sys->firesms($message, $phone, $phone);
        //return redirect("http://admissions.ttuportal.com");
        return redirect()->back();

    }

    public function getStudentProgram(Request $request, $program)
    {
        header('Content-Type: application/json');
        $indexno = $request->input("student");
        $data = @Models\StudentModel::where("PROGRAMMECODE", $program)->get();
        return response()->json(array('data' => $data));

    }

    public function getStudentHall(Request $request)
    {
        header('Content-Type: application/json');
        $indexno = $request->input("student");
        $data = @ApplicantModel::where("APPLICATION_NUMBER", $indexno)->first();
        if (!empty($data)) {
            return $data->HALL_ADMITTED;
        } else {
            return "Non Resident";
        }

    }


    public function qualityAssurance(Request $request, $indexno)
    {
        @StudentModel::where("INDEXNO", $indexno)->update(array("QUALITY_ASSURANCE" => 1));
        // return $this->response->json("status","Student Lecturer Assessment received at main system");
        return Response::json("Student Lecturer Assessment received at main system", "200");
    }

    public function liaison(Request $request, $indexno)
    {
        @ StudentModel::where("INDEXNO", $indexno)->update(array("LIAISON" => 1));
        return Response::json("Student Liaison forms received at main system", "200");
    }

    public function getReceipt()
    {
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

    public function updateReceipt()
    {
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

    public function payFeeLive(Request $request, SystemController $sys)
    {
        header('Content-Type: application/json');
        $bankAuth = ["128ashbx393932", "1nm383ypmwd123"];
        $indexno = $request->input("indexno");
        $amount = $request->input("amount");
        $bank = $request->input("accountNumber");
        $type = $request->input("fee_type");
        $transactionId = $request->input("transactionId");
        $date = $request->input("transactionDate");
        $auth = $request->input("auth");
        $array = $sys->getSemYear();
        $sem = $array[0]->SEMESTER;
        $year = $array[0]->YEAR;

        \DB::beginTransaction();
        try {

            if (in_array($auth, $bankAuth)) {

                $data = @StudentModel::where("INDEXNO", $indexno)->orWhere("STNO", $indexno)->first();

                $bankDetail = @Models\BankModel::where("ACCOUNT_NUMBER", $bank)->first();

                if ($bankDetail) {

                    if (!empty($data)) {

                        if (!empty($data)) {
                            // $bill = $sys->getYearBill($year, $data->LEVEL, $data->PROGRAMMECODE);
                            $billOwing = $data->BILL_OWING;
                            $owing = $billOwing - $amount;
                            if ($billOwing <= $amount) {
                                $details = "Full payment";


                            } else {
                                $details = "Part payment";
                            }
                            $paid = $data->PAID + $amount;
                            $que = Models\PortalPasswordModel::where("username", $indexno)->first();
                            if (empty($que) && !empty($indexno)) {
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
                                $phone = $data->TELEPHONENO;
                                $fname = $data->FIRSTNAME;

                                $message = "Online credential: visit records.ttuportal.com with $indexno as your username  and $real as password and follow the course registration steps.";


                                // @$sys->firesms($message, $phone, $indexno);

                                \DB::commit();

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
                            $feeLedger->RECIEPIENT = "API_CALL";
                            $feeLedger->BANK = $bank;
                            $feeLedger->TRANSACTION_ID = $transactionId;
                            $feeLedger->RECEIPTNO = $receipt;
                            $feeLedger->YEAR = $year;
                            $feeLedger->FEE_TYPE = $type;
                            $feeLedger->SEMESTER = $sem;
                            if ($feeLedger->save()) {

                                @StudentModel::where("INDEXNO", $indexno)->orWhere("STNO", $indexno)->update(array("BILL_OWING" => $owing, "PAID" => $paid));
                                @$this->updateReceipt();
                                \DB::commit();
                                //return Response::json("Success", "01");
                                header('Content-Type: application/json');
                                // return  json_encode(array('responseCode'=>'01','responseMessage'=>'Successfully Processed'));
                                return response()->json(array('responseCode' => '01', 'responseMessage' => 'Successfully Processed'));

                            } else {
                                header('Content-Type: application/json');
                                // return  json_encode(array('responseCode'=>'09','responseMessage'=>'Failed'));
                                return response()->json(array('responseCode' => '09', 'responseMessage' => 'Failed'));
                            }

                        } else {

                            return response()->json(array('responseCode' => '09', 'responseMessage' => 'Student or Applicant does not exist'));

                        }


                    } else {

                        return response()->json(array('responseCode' => '09', 'responseMessage' => 'Student or Applicant does not exist'));

                    }


                } else {
                    return response()->json(array('responseCode' => '08', 'responseMessage' => 'Bank Account does not exist'));

                }
            } else {
                return response()->json(array('responseCode' => '08', 'responseMessage' => 'Unknown Bank Entity'));

            }
        } catch (\Exception $e) {
            \DB::rollback();
        }

    }


    public function payFee(Request $request, SystemController $sys)
    {
        header('Content-Type: application/json');
        $bankAuth = ["128ashbx393932", "1nm383ypmwd123"];
        $indexno = $request->input("indexno");
        $amount = $request->input("amount");
        $bank = $request->input("accountNumber");
        $type = $request->input("fee_type");
        $transactionId = $request->input("transactionId");
        $date = $request->input("transactionDate");
        $auth = $request->input("auth");
        $array = $sys->getSemYear();
        $sem = $array[0]->SEMESTER;
        $year = $array[0]->YEAR;

        \DB::beginTransaction();
        try {

            $data = @StudentModel::where("INDEXNO", $indexno)->orWhere("STNO", $indexno)->first();

            $bankDetail = @Models\BankModel::where("ACCOUNT_NUMBER", $bank)->first();

            if ($bankDetail) {

                if (!empty($data)) {

                    if (!empty($data)) {
                        // $bill = $sys->getYearBill($year, $data->LEVEL, $data->PROGRAMMECODE);
                        $billOwing = $data->BILL_OWING;
                        $owing = $billOwing - $amount;
                        if ($billOwing <= $amount) {
                            $details = "Full payment";


                        } else {
                            $details = "Part payment";
                        }
                        $paid = $data->PAID + $amount;
                        $que = Models\PortalPasswordModel::where("username", $indexno)->first();
                        if (empty($que) && !empty($indexno)) {
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
                            $phone = $data->TELEPHONENO;
                            $fname = $data->FIRSTNAME;

                            $message = "Online credential: visit records.ttuportal.com with $indexno as your username  and $real as password and follow the course registration steps.";


                            // @$sys->firesms($message, $phone, $indexno);

                            \DB::commit();

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
                        $feeLedger->RECIEPIENT = "API_CALL";
                        $feeLedger->BANK = $bank;
                        $feeLedger->TRANSACTION_ID = $transactionId;
                        $feeLedger->RECEIPTNO = $receipt;
                        $feeLedger->YEAR = $year;
                        $feeLedger->FEE_TYPE = $type;
                        $feeLedger->SEMESTER = $sem;
                        if ($feeLedger->save()) {

                            @StudentModel::where("INDEXNO", $indexno)->orWhere("STNO", $indexno)->update(array("BILL_OWING" => $owing, "PAID" => $paid));
                            @$this->updateReceipt();
                            \DB::commit();
                            //return Response::json("Success", "01");
                            header('Content-Type: application/json');
                            // return  json_encode(array('responseCode'=>'01','responseMessage'=>'Successfully Processed'));
                            return response()->json(array('responseCode' => '01', 'responseMessage' => 'Successfully Processed'));

                        } else {
                            header('Content-Type: application/json');
                            // return  json_encode(array('responseCode'=>'09','responseMessage'=>'Failed'));
                            return response()->json(array('responseCode' => '09', 'responseMessage' => 'Failed'));
                        }

                    } else {

                        return response()->json(array('responseCode' => '09', 'responseMessage' => 'Student or Applicant does not exist'));

                    }


                } else {

                    return response()->json(array('responseCode' => '09', 'responseMessage' => 'Student or Applicant does not exist'));

                }


            } else {
                return response()->json(array('responseCode' => '08', 'responseMessage' => 'Bank Account does not exist'));

            }
        } catch (\Exception $e) {
            \DB::rollback();
        }


    }


    /**
     * Destroy the given task.
     *
     * @param  Request $request
     * @param  Task $task
     * @return Response
     */
    public function destroy(Request $request, Task $task)
    {
        $this->authorize('destroy', $task);

        $task->delete();

        return redirect('/tasks');
    }

}
