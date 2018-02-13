<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\FeeModel;
use App\Models\FeePaymentModel;
use App\Models\StudentModel;
use App\Models;
use App\Models\ReceiptModel;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Excel;
use App\User;
use Charts;

class QualityAssuranceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');


    }



    public function printIndividualLecturer(Request $request, SystemController $sys)
    {
        $query= Models\QAquestionModel::groupBy('coursecode')->groupBy('academic_year');
        if ($request->has('level') && trim($request->input('level')) != "") {
            $query->where("level", $request->input("level", ""));
        }
        if ($request->has('semester') && trim($request->input('semester')) != "") {
            $query->where("sem", $request->input("sem", ""));
        }
        if ($request->has('lecturer') && trim($request->input('lecturer')) != "") {
            $query->where("lecturer", $request->input("lecturer", ""));
        }
        if ($request->has('year') && trim($request->input('year')) != "") {
            $query->where("academic_year", $request->input("year", ""));
        }
        if ($request->has('program') && trim($request->input('program')) != "") {
            $query->whereHas('studentDetials', function($q)use ($request) {
                $q->whereHas('programme', function($q )use ($request) {
                    $q->whereIn('PROGRAMMECODE', array($request->input("program", "")));
                });
            }) ;
           }
        $data = $query->orderBy("academic_year","desc")->paginate(200);
        $request->flashExcept("_token");

        $programme = $sys->getProgramList();
        $allLectureres=$sys->getLectureList_All();
        return view("qa.index")
            ->with("data", $data)->with('program', $programme)->with('level', $sys->getLevelList())
            ->with('lecturer', $allLectureres)
            ->with('department', $sys->getDepartmentList())
            ->with('year', $sys->years());


    }

    public function showBulkReport(Request $request, SystemController $sys)
    {
        $data = $sys->getProgramList();
        return view("admissions.bulkAdmissionLetter")
            ->with("data", $data);

    }


}