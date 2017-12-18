<?php

namespace App\Http\Controllers;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models;
use App\User;
use App\Models\AcademicRecordsModel;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Excel;

class CourseController extends Controller
{

    /**
     * Create a new controller instance.
     *

     * @return void
     */
    public function __construct()
    {
        set_time_limit(36000);
        ini_set('max_input_vars', '9000');
        $this->middleware('auth');


    }
    public function log_query() {
        \DB::listen(function ($sql, $binding, $timing) {
            \Log::info('showing query', array('sql' => $sql, 'bindings' => $binding));
        }
        );
    }
    public function printCards(Request $request,SystemController $sys){
        if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='top' || @\Auth::user()->department=='Tptop'|| @\Auth::user()->role=='Dean' || @\Auth::user()->role=='Lecturer' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop'){
            if ($request->isMethod("get")) {
                $program=$sys->getProgramList();

                return view('courses.cardview')
                    ->with('program',$program)->with('year',$sys->years())->with('level', $sys->getLevelList());
            }
            else{

                $program = $request->input('program');

                $level = $request->input('level');

                // $query = Models\AcademicRecordsModel::where("code", $course)->where('year',$year)->where('sem',$semester)->where('level',$level)->paginate(100);

                // $query = Models\StudentModel::where("PROGRAMMECODE",$program)->where("LEVEL",$level)->where("STATUS","In School")->get();
// dd($mark);

                $url = url('/printCards/'.$program.'/program/'.$level.'/level');

                $print_window = "<script >window.open('$url','','location=1,status=1,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=500')</script>";
                $request->session()->flash("success",
                    "    $print_window");
                return redirect("/print/cards");



            }
        }
        else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    public function processCards(Request $request,$program ,$level) {
        //$year=\Session::get('year');
        $query = Models\StudentModel::where("PROGRAMMECODE",$program)->where("LEVEL",$level)->where("STATUS","In School")->get();

        return view('courses.printCard')->with('mark', $query)->with("program",$program)
            ->with("level", $level)
            ;


    }
    public function checkMountedCourses(Request $request,SystemController $sys) {
        $array = $sys->getSemYear();
        $sem = $array[0]->SEMESTER;
        $year = $array[0]->YEAR;
        if ($request->isMethod("get")) {
            $hod = \Auth::user()->fund;

            $courses = Models\MountedCourseModel::where('COURSE', '!=', '')->where("COURSE_SEMESTER",$sem)->where("COURSE_YEAR",$year)->whereHas('courses', function($q) {
                $q->whereHas('programs', function($q) {
                    $q->whereIn('DEPTCODE', array(@\Auth::user()->department));
                });
            })->paginate(100);
            return view('courses.hodChecker')->with('data', $courses) ;

        } else{
            $hod=\Auth::user()->fund;
            $checked=1;
            $check= Models\MountedCourseCheckerModel::where("hod",$hod)->where("sem",$sem)
                ->where("year",$year)->first();
            if($check==""){
                $checker=new Models\MountedCourseCheckerModel();
                $checker->hod=$hod;
                $checker->sem=$sem;
                $checker->year=$year;
                $checker->checked=$checked;
                $checker->save();
            }
            else{
                Models\MountedCourseCheckerModel::where("hod",$hod)->where("sem",$sem)
                    ->where("year",$year)->update(array("checked"=>$checked));
            }
            return   redirect('mounted_view')->with("success", " <span style='font-weight:bold;font-size:13px;'>Courses has been verified for registration. Thanks!</span> ");

        }

    }
    public function processCourseUploads(Request $request,SystemController $sys) {


        set_time_limit(36000);




        $valid_exts = array('csv','xls','xlsx'); // valid extensions
        $file = $request->file('file');
        $name = time() . '-' . $file->getClientOriginalName();
        if (!empty($file)) {

            $ext = strtolower($file->getClientOriginalExtension());

            if (in_array($ext, $valid_exts)) {
                // Moves file to folder on server
                // $file->move($destination, $name);

                $path = $request->file('file')->getRealPath();
                $data = Excel::load($path, function($reader) {

                })->get();
                $total=count($data);

                if(!empty($data) && $data->count()){

                    $user = \Auth::user()->id;
                    foreach($data as $value=>$row)
                    {
                        $code=$row->course_code;
                        $program=$row->programme;
                        $credit=$row->course_credit;
                        $name=  strtoupper($row->course_name);
                        $year=$row->course_level;
                        $semester=$row->course_semester;

                        $programme = $sys->programmeSearchByCode(); // check if the programmes in the file tally wat is in the db
                        if (in_array($program, $programme)) {

                            $testQuery=Models\CourseModel::where('COURSE_CODE', $code)->first();

                            if(empty($testQuery)){


                                $course = new Models\CourseModel();
                                $course->COURSE_CODE = $code;
                                $course->COURSE_NAME = $name;
                                $course->COURSE_CREDIT = $credit;
                                $course->PROGRAMME = $program;
                                $course->COURSE_SEMESTER = $semester;
                                $course->COURSE_LEVEL = $year;

                                $course->USER = $user;
                                $course->save();
                                \DB::commit();
                            }
                            else{

                                Models\CourseModel::where('COURSE_CODE', $code)->update(array("COURSE_LEVEL" =>@$year, "COURSE_SEMESTER" => $semester, "PROGRAMME" => $program,  "COURSE_CREDIT" =>$credit,"COURSE_NAME"=>$name,"USER"=>$user ));
                                \DB::commit();
                            }
                        }
                        else{
                            redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognize programme.please try again!</span> ");

                        }





                    }
                }
            } else {
                return redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>Only excel file is accepted!</span> ");

            }
        } else {
            return redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload an excel file!</span> ");

        }


        return redirect('/courses')->with("success", " <span style='font-weight:bold;font-size:13px;'>$total Courses uploaded successfully</span> ");

    }
    public function processMountedUpload(Request $request,SystemController $sys) {

        if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Support' || @\Auth::user()->role == 'Admin'|| @\Auth::user()->role == 'Registrar' || @\Auth::user()->department == 'top' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop') {



            set_time_limit(36000);

            $valid_exts = array('csv', 'xls', 'xlsx'); // valid extensions
            $file = $request->file('file');
            $name = time() . '-' . $file->getClientOriginalName();
            if (!empty($file)) {

                $ext = strtolower($file->getClientOriginalExtension());

                if (in_array($ext, $valid_exts)) {
                    // Moves file to folder on server
                    // $file->move($destination, $name);

                    $path = $request->file('file')->getRealPath();
                    $data = Excel::load($path, function($reader) {

                    })->get();
                    $total = count($data);

                    if (!empty($data) && $data->count()) {

                        $user = \Auth::user()->id;
                        $courseError=array();
                        $programError=array();
                        foreach ($data as $value => $row) {

                            $code = $row->code;
                            $program = $row->program;
                            $courseID=$sys->getCourseByCode2($code,$program);
                            $credit = $row->credit;
                            $type = $row->type;
                            $level = $row->level;
                            $name = $row->course;
                            $year = $row->year;
                            $semester = $row->semester;
                            $searchCourse = $sys->courseSearchByCode();
                            $programme = $sys->programmeSearchByCode(); // check if the programmes in the file tally wat is in the db
                            if (in_array($program, $programme)) {
                                if (in_array($code, $searchCourse)) {
                                    $testQuery = Models\MountedCourseModel::where('COURSE', $courseID)->where("COURSE_YEAR",$year)
                                        ->where("COURSE_SEMESTER",$semester)
                                        ->first();

                                    if (empty($testQuery)) {


                                        $course = new Models\MountedCourseModel();
                                        $course->COURSE = $courseID;
                                        $course->COURSE_CODE = $code;
                                        $course->COURSE_CREDIT = $credit;
                                        $course->PROGRAMME = $program;
                                        $course->COURSE_SEMESTER = $semester;
                                        $course->COURSE_LEVEL = $level;
                                        $course->COURSE_TYPE = $type;
                                        $course->COURSE_YEAR = $year;
                                        $course->MOUNTED_BY = $user;
                                        $course->save();
                                        \DB::commit();
                                    } else {

                                        Models\MountedCourseModel::where('COURSE', $courseID)->update(array("COURSE_CODE" =>$code,"COURSE_LEVEL" =>$level, "COURSE_SEMESTER" => $semester, "PROGRAMME" => $program, "COURSE_CREDIT" => $credit, "COURSE_TYPE" => $type, "COURSE_YEAR" => $year,"MOUNTED_BY" => $user));
                                        \DB::commit();
                                    }
                                } else {
                                    array_push($courseError, $name." ".$code);
                                    //  redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognize courses.please try again!</span> ");
                                    //  dd($courseError);
                                    continue;
                                }
                            } else {
                                array_push($programError, $sys->getProgram($program));
                                continue;
                                // redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognize programme.please try again!</span> ");
                            }
                        }
                        if(!empty($programError) || !empty($courseError)){
                            return     redirect('/upload/mounted')->with("errorP",$programError)
                                ->with("errorC",$courseError);

                        }
                    }
                } else {
                    return redirect('/upload/mounted')->with("error", " <span style='font-weight:bold;font-size:13px;'>Only excel file is accepted!</span> ");
                }
            } else {
                return redirect('/upload/mounted')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload an excel file!</span> ");
            }
            return redirect('/mounted_view')->with("success", " <span style='font-weight:bold;font-size:13px;'>$total Courses mounted successfully</span> ");

        }
        else {

            return redirect("/dashboard");
        }






    }

    public function processCourseUpload(Request $request,SystemController $sys) {


        set_time_limit(36000);

        $valid_exts = array('csv', 'xls', 'xlsx'); // valid extensions
        $file = $request->file('file');
        $name = time() . '-' . $file->getClientOriginalName();
        if (!empty($file)) {
            $ext = strtolower($file->getClientOriginalExtension());
            if (in_array($ext, $valid_exts)) {
                // Moves file to folder on server
                // $file->move($destination, $name);
                $path = $request->file('file')->getRealPath();
                $data = Excel::load($path, function($reader) {

                })->get();
                $total = count($data);
                if (!empty($data) && $data->count()) {
                    $user = \Auth::user()->id;
                    $courseError=array();
                    $programError=array();
                    foreach ($data as $value => $row) {

                        $code = $row->code;
                        $program = $row->program;
                        $courseID=$sys->getCourseByCode2($code,$program);
                        $credit = $row->credit;
                        $type = $row->type;
                        $level = $row->level;
                        $name = $row->course;
                        $year = $row->year;
                        $semester = $row->semester;
                        $searchCourse = $sys->courseSearchByCode();
                        $programme = $sys->programmeSearchByCode(); // check if the programmes in the file tally wat is in the db
                        if (in_array($program, $programme)) {
                            if (in_array($code, $searchCourse)) {
                                $testQuery = Models\MountedCourseModel::where('COURSE', $courseID)->where("COURSE_YEAR",$year)
                                    ->where("COURSE_SEMESTER",$semester)
                                    ->first();
                                if (empty($testQuery)) {
                                    $course = new Models\MountedCourseModel();
                                    $course->COURSE = $courseID;
                                    $course->COURSE_CODE = $code;
                                    $course->COURSE_CREDIT = $credit;
                                    $course->PROGRAMME = $program;
                                    $course->COURSE_SEMESTER = $semester;
                                    $course->COURSE_LEVEL = $level;
                                    $course->COURSE_TYPE = $type;
                                    $course->COURSE_YEAR = $year;
                                    $course->MOUNTED_BY = $user;
                                    $course->save();
                                    \DB::commit();
                                } else {
                                    Models\MountedCourseModel::where('COURSE', $courseID)->update(array("COURSE_CODE" =>$code,"COURSE_LEVEL" =>$level, "COURSE_SEMESTER" => $semester, "PROGRAMME" => $program, "COURSE_CREDIT" => $credit, "COURSE_TYPE" => $type, "COURSE_YEAR" => $year,"MOUNTED_BY" => $user));
                                    \DB::commit();
                                }
                            } else {
                                array_push($courseError, $name." ".$code);
                                //  redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognize courses.please try again!</span> ");
                                //  dd($courseError);
                                continue;
                            }
                        } else {
                            array_push($programError, $sys->getProgram($program));
                            continue;
                            // redirect('/upload/courses')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognize programme.please try again!</span> ");
                        }
                    }
                    if(!empty($programError) || !empty($courseError)){
                        return     redirect('/upload/mounted')->with("errorP",$programError)
                            ->with("errorC",$courseError);

                    }
                }
            } else {
                return redirect('/upload/mounted')->with("error", " <span style='font-weight:bold;font-size:13px;'>Only excel file is accepted!</span> ");
            }
        } else {
            return redirect('/upload/mounted')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload an excel file!</span> ");
        }



        return redirect('/mounted_view')->with("success", " <span style='font-weight:bold;font-size:13px;'>$total Courses mounted successfully</span> ");

    }
    public function uploadMounted(Request $request,SystemController $sys){

        if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Support' || @\Auth::user()->role == 'Admin'|| @\Auth::user()->role == 'Registrar' || @\Auth::user()->department == 'top' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop') {



            return view('courses.uploadMounted');




        } else {

            return redirect("/dashboard");
        }
    }
    public function processUpdateMounted(SystemController $sys,Request $request) {
        \DB::beginTransaction();
        try {

            $upper = $request->input('upper');

            $sem = $request->input('semester');
            $credit = $request->input('credit');
            $level = $request->input('level');
            $type = $request->input('type');
            $course = $request->input('course');

            $lecturer = $request->input('lecturer');

            $key = $request->input('key');
            for ($i = 0; $i < $upper; $i++) {
                $courseArr = $course[$i];

                $levelArr = $level[$i];
                $semArr = $sem[$i];
                $creditArr = $credit[$i];
                $typeArr = $type[$i];
                $keyArr = $key[$i];
                $lecturerArr = $lecturer[$i];

                Models\MountedCourseModel::where("ID", $key)
                    ->update(array("COURSE_CREDIT" => $creditArr,   "COURSE_SEMESTER" => $semArr, "COURSE_TYPE" => $typeArr, "COURSE_LEVEL" => $levelArr, "LECTURER" => $lecturerArr));

                \DB::commit();
                Models\AcademicRecordsModel::where("course", $key)->update(array("credits" => $creditArr,   "sem" => $semArr, "level" => $levelArr, "lecturer" => $lecturerArr));

            }
            return redirect("/mounted_view")->with("success","Mounted courses updated successfully");
        }
        catch (\Exception $e) {
            \DB::rollback();
        }
    }
    public function updateMounted(SystemController $sys,Request $request, $id) {
        if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Support'|| @\Auth::user()->role == 'Admin'|| @\Auth::user()->role == 'Registrar' || @\Auth::user()->department == 'top' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop') {


            $lecturers=$sys->getLectureList_All();
            $yearList=$sys->years();
            $program=$sys->getProgramList();
            $course=$sys->getCourseList();
            $user=@\Auth::user()->fund;
            $array = $sys->getSemYear();
            $sem = $array[0]->SEMESTER;
            $year = $array[0]->YEAR;
            $query=  @Models\MountedCourseModel::query()
                ->where("COURSE_YEAR",$year)
                ->where("ID",$id)
                ->where("COURSE_SEMESTER",$sem)->paginate(20);


            return view('courses.editMounted')->with("data",@$query)
                ->with("lecturer",$lecturers)
                ->with("program",$program)
                ->with("course",$course)
                ->with("ID",$id)
                ->with('level', $sys->getLevelList())
                ->with("years",$yearList);



        }
        else{
            return  redirect()->back();
        }


    }
    public function gadoo(SystemController $sys,Request $request){
        if ($request->isMethod("get")) {



            $programme = $sys->getProgramList();
            $course = $sys->getCourseList();

            return view('courses.legacyGrades')->with('level', $sys->getLevelList())->with('program', $programme)->with('level', $sys->getLevelList())
                ->with('course', $course)->with('year', $sys->years());


        }
        else{
            $destination = "public/upload";

            $handle = fopen($_FILES['file']['tmp_name'], "r");

            move_uploaded_file($_FILES["file"]["tmp_name"], $destination);
            $data = array(); // data array


            $row = 0;
            $columns = [];
            $rows = [];
            // first get the headers into an array
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE AND $row==0) {
                $columns = $data;
                $row++;

                // while get the columns start reading the rows too
                while ($file_line = fgetcsv($handle,1000,",","'")){

                    $totalRecords = count($file_line);

                    for ($c = 0; $c <$totalRecords; $c++) {
                        $col[$c] = $file_line[$c];
                    }
                    // we dont need the index no column so we remove it using unset which is the
                    // first element of the header array
                    unset($columns[0]);

                    $aggie = 1;
                    //dd($columns);
                    foreach ($columns as    $name){

                        $course=$name;
                        $exam=$col[$aggie];
                        $total=$col[$aggie];
                        $indexNo=$col[0];
                        $studentDb= $indexNo  ;
                        $studentID= $sys->getStudentIDfromIndexno($indexNo);

                        $courseName=$sys->getCourseCodeByIDArray2($course);

                        $displayCourse=$courseName[0]->COURSE_NAME;
                        $displayCode=$courseName[0]->COURSE_CODE;
                        $studentSearch = $sys->studentSearchByIndexNo($programme); // check if the students in the file tally
                        /*check if the students in the file tally with  students records
                         * this is done so that users don't upload results of students that
                         * are not in the system
                         */

                        //if (@in_array($studentDb, $studentSearch)) {


                        $total= round($exam,2);
                        $programmeDetail=$sys->getCourseProgramme2($course);

                        $program=$sys->getProgramArray($programmeDetail);
                        $gradeArray = @$sys->getGrade($total, $program[0]->GRADING_SYSTEM);
                        $grade = @$gradeArray[0]->grade;
                        $credit=$sys->getCreditHour($name,$semester,$level,$program[0]->PROGRAMMECODE); // get credit hour of a course


                        $gradePoint = @$gradeArray[0]->value;
                        $test=Models\AcademicRecordsModel::where("indexno",$indexNo)->where("level",$level)->where("sem",$semester)->where("code",$course)->where("credits",$credit)->where("year",$year)->get()->toArray();
                        if(empty($test)){
                            $record = new Models\AcademicRecordsModel();
                            $record->indexno = $indexNo;
                            $record->code = $course;
                            $record->sem = $semester;
                            $record->year = $year;
                            $record->credits = $credit;
                            $record->student= $studentID;
                            $record->level = $level;

                            $record->exam = $exam;
                            $record->total = $total;

                            $record->grade = $grade;
                            $record->gpoint =round(( $credit*$gradePoint),2);
                            $record->save();

                            $cgpa= number_format(@(( $credit*$gradePoint)/$credit), 2, '.', ',');
                            $oldCgpa= @Models\StudentModel::where("INDEXNO",$indexNo)->select("CGPA")->first();

                            $newCgpa=$cgpa+@$oldCgpa->CGPA;

                            Models\StudentModel::where("INDEXNO",$indexNo)->update(array("CGPA"=>$newCgpa));
                            \DB::commit();

                        }
                        $aggie++;
                    }
                }
            }
        }
    }
    public function processResit(SystemController $sys,Request $request) {

        $this->validate($request, [

            'file' => 'required',

            'sem' => 'required',
            'year' => 'required',

            'program' => 'required',
            'level' => 'required',
        ]);
        $valid_exts = array('csv'); // valid extensions
        $file = $request->file('file');
        $path = $request->file('file')->getRealPath();

        $ext = strtolower($file->getClientOriginalExtension());
        $semester = $request->input('sem');
        $year = $request->input('year');

        $programme = $request->input('program');
        $level = $request->input('level');
        //$credit=$request->input('credit');
        if (in_array($ext, $valid_exts)) {
            $destination = "public/upload";

            $handle = fopen($_FILES['file']['tmp_name'], "r");

            // move_uploaded_file($_FILES["file"]["tmp_name"], $destination);
            $data = array(); // data array


            $row = 0;
            $columns = [];
            $rows = [];
            // first get the headers into an array
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE AND $row==0) {
                $columns = $data;
                $row++;

                // while get the columns start reading the rows too
                while ($file_line = fgetcsv($handle,1000,",","'")){

                    $totalRecords = count($file_line);

                    for ($c = 0; $c <$totalRecords; $c++) {
                        $col[$c] = $file_line[$c];
                    }
                    // we dont need the index no column so we remove it using unset which is the
                    // first element of the header array
                    unset($columns[0]);

                    $aggie = 1;
                    // dd($columns);
                    foreach ($columns as    $name){

//                                $gad=new Models\GadModel();
//                                 $gad->indexno=$col[0];
//                                 $gad->course=$name;
//                                  $gad->grade=$col[$aggie];
//                                  $gad->save();



                        $course=$name;
                        $exam=$col[$aggie];
                        $total=$col[$aggie];
                        $indexNo=$col[0];
                        $studentDb= $indexNo  ;
                        $studentID= $sys->getStudentIDfromIndexno($indexNo);



                        $courseName=@$sys->getCourseCodeByIDArray2($course);

                        $displayCourse=@$courseName[0]->COURSE_NAME;
                        $displayCode=@$courseName[0]->COURSE_CODE;
                        $studentSearch = @$sys->studentSearchByIndexNo($programme); // check if the students in the file tally
                        /*check if the students in the file tally with  students records
                         * this is done so that users don't upload results of students that
                         * are not in the system
                         */



                        $total= round($exam,2);
                        $programmeDetail=$sys->getCourseProgramme2($course);

                        $program=$sys->getProgramArray($programmeDetail);
                        $gradeArray = @$sys->getGrade($total, $program[0]->GRADING_SYSTEM);
                        $grade = @$gradeArray[0]->grade;

                        $credit=$sys->getCreditHour($name,$semester,$level,$program[0]->PROGRAMMECODE); // get credit hour of a course

                        $gradePoint = @$gradeArray[0]->value;
                        $test=Models\AcademicRecordsModel::where("indexno",$indexNo)->where("level",$level)->where("sem",$semester)->where("code",$course)->where("credits",$credit)->where("year",$year)->where("resit","yes")->get()->toArray();
                        if(empty($test)){
                            if($total>0 || $total!=""){
                                $record = new Models\AcademicRecordsModel();
                                $record->indexno = $indexNo;
                                $record->code = $course;
                                $record->sem = $semester;
                                $record->year = $year;
                                $record->credits = $credit;
                                $record->student= $studentID;
                                $record->level = $level;

                                $record->exam = $exam;
                                $record->total = $total;
                                $record->resit = "yes";

                                $record->grade = $grade;
                                $record->gpoint =round(( $credit*$gradePoint),2);
                                $record->save();

                                $cgpa= number_format(@(( $credit*$gradePoint)/$credit), 2, '.', ',');
                                //$oldCgpa= @Models\StudentModel::where("INDEXNO",$indexNo)->select("CGPA")->first();

                                //$newCgpa=$cgpa+@$oldCgpa->CGPA;

                                //@Models\StudentModel::where("INDEXNO",$indexNo)->update(array("CGPA"=>$newCgpa));
                                // \DB::commit();
                            }
                        }







                        $aggie++;
                    }


                }
            }
        }



        else{
            return redirect('upload/resit')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload only CSV   file!</span> ");
        }
        return redirect('/dashboard')->with("success",  " <span style='font-weight:bold;font-size:13px;'> Marks  successfully uploaded !</span> ");


    }
    public function uploadResit(SystemController $sys,Request $request){
        if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='top' || @\Auth::user()->department=='Tptop'|| @\Auth::user()->role=='Dean' || @\Auth::user()->role=='Support' || @\Auth::user()->role=='Registrar' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop'){

            if ($request->isMethod("get")) {



                $programme = $sys->getProgramList();
                // $course = $sys->getCourseList();

                return view('courses.uploadResit')->with('level', $sys->getLevelList())->with('program', $programme)->with('level', $sys->getLevelList())
                    ->with('year', $sys->years());


            }

        }


        else{
            return redirect("/dashboard");
        }
    }
    public function processsUploadLegacy(SystemController $sys,Request $request) {

        $this->validate($request, [

            'file' => 'required',

            'sem' => 'required',
            'year' => 'required',

            'program' => 'required',
            'level' => 'required',
        ]);
        $valid_exts = array('csv'); // valid extensions
        $file = $request->file('file');
        $path = $request->file('file')->getRealPath();

        $ext = strtolower($file->getClientOriginalExtension());
        $semester = $request->input('sem');
        $year = $request->input('year');

        $programme = $request->input('program');
        $level = $request->input('level');
        //$credit=$request->input('credit');
        if (in_array($ext, $valid_exts)) {
            $destination = "public/upload";

            $handle = fopen($_FILES['file']['tmp_name'], "r");

            //move_uploaded_file($_FILES["file"]["tmp_name"], $destination);
            $data = array(); // data array


            $row = 0;
            $columns = [];
            $rows = [];
            // first get the headers into an array
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE AND $row==0) {
                $columns = $data;
                $row++;

                // while get the columns start reading the rows too
                while ($file_line = fgetcsv($handle,1000,",","'")){

                    $totalRecords = count($file_line);

                    for ($c = 0; $c <$totalRecords; $c++) {
                        $col[$c] = $file_line[$c];
                    }
                    // we dont need the index no column so we remove it using unset which is the
                    // first element of the header array
                    unset($columns[0]);

                    $aggie = 1;
                    //dd($columns);
                    foreach ($columns as    $name){

//                                $gad=new Models\GadModel();
//                                 $gad->indexno=$col[0];
//                                 $gad->course=$name;
//                                  $gad->grade=$col[$aggie];
//                                  $gad->save();



                        $course=$name;
                        $exam=$col[$aggie];
                        $total=$col[$aggie];
                        $indexNo=$col[0];
                        $studentDb= $indexNo  ;
                        $studentID= $sys->getStudentIDfromIndexno($indexNo);


                        $courseName=@$sys->getCourseCodeByIDArray2($course);

                        $displayCourse=@$courseName[0]->COURSE_NAME;
                        $displayCode=@$courseName[0]->COURSE_CODE;
                        $studentSearch = @$sys->studentSearchByIndexNo($programme); // check if the students in the file tally
                        /*check if the students in the file tally with  students records
                         * this is done so that users don't upload results of students that
                         * are not in the system
                         */

//                                     if (@in_array($studentDb, $studentSearch)) {
//
//
                        $total= round($exam,2);
                        $programmeDetail=$sys->getCourseProgramme2($course);

                        $program=$sys->getProgramArray($programmeDetail);
                        $gradeArray = @$sys->getGrade($total, $program[0]->GRADING_SYSTEM);
                        $grade = @$gradeArray[0]->grade;
                        $credit=$sys->getCreditHour($name,$semester,$level,$program[0]->PROGRAMMECODE); // get credit hour of a course


                        $gradePoint = @$gradeArray[0]->value;
                        $test=@Models\AcademicRecordsModel::where("indexno",$indexNo)->where("level",$level)->where("sem",$semester)->where("code",$course)->where("credits",$credit)->where("year",$year)->get()->toArray();
                        if(empty($test)){
                            $record = new Models\AcademicRecordsModel();
                            $record->indexno = $indexNo;
                            $record->code = $course;
                            $record->sem = $semester;
                            $record->year = $year;
                            $record->credits = $credit;
                            $record->student= $studentID;
                            $record->level = $level;

                            $record->exam = $exam;
                            $record->total = $total;

                            $record->grade = $grade;
                            $record->gpoint =round(( $credit*$gradePoint),2);
                            $record->save();

                            $cgpa= number_format(@(( $credit*$gradePoint)/$credit), 2, '.', ',');
                            //@$oldCgpa= @Models\StudentModel::where("INDEXNO",$indexNo)->select("CGPA")->first();

                            // $newCgpa=$cgpa+@$oldCgpa->CGPA;

                            //@Models\StudentModel::where("INDEXNO",$indexNo)->update(array("CGPA"=>$newCgpa));
                            //\DB::commit();

                        }
                        else{

                            Models\AcademicRecordsModel::where("indexno",$indexNo)->where("level",$level)->where("sem",$semester)->where("code",$course)->where("credits",$credit)->where("year",$year)->update(
                                array(
                                    "indexno" =>$indexNo,
                                    "code"=>$course,
                                    "sem" =>$semester,
                                    "year"=>$year,
                                    "credits"=>$credit,
                                    "student"=>$studentID,
                                    "level"=>$level,

                                    "exam" =>$exam,
                                    "total"=> $total,

                                    "grade" => $grade,
                                    "gpoint" =>round(( $credit*$gradePoint),2),
                                )

                            );
                            //  $cgpa= @number_format(@(( $credit*$gradePoint)/$credit), 2, '.', ',');

                            //$oldCgpa= @Models\StudentModel::where("INDEXNO",$indexNo)->select("CGPA")->first();
                            //$newCgpa=@$cgpa+@$oldCgpa->CGPA;

                            //  @Models\StudentModel::where("INDEXNO",$indexNo)->update(array("CGPA"=>$newCgpa));

                            // \DB::commit();
                        }





//                               }
//                              else{
//                                  // continue;
//                                     return redirect('upload/legacy')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognized students for $programme .Please upload only  students for  $programme!</span> ");
//                             
//                                 
//                            } 
                        $aggie++;
                    }


                }
            }
        }



        else{
            return redirect('upload/legacy')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload only CSV   file!</span> ");
        }
        return redirect('/upload/legacy')->with("success",  " <span style='font-weight:bold;font-size:13px;'> Marks  successfully uploaded !</span> ");


    }

    public function uploadGad2(SystemController $sys,Request $request){
        if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='top' || @\Auth::user()->department=='Tptop'|| @\Auth::user()->role=='Dean' || @\Auth::user()->role=='Support' || @\Auth::user()->role=='Registrar' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop'){




            $programme = $sys->getProgramList();
            // $course = $sys->getCourseList();

            return view('courses.legacyGrades')->with('level', $sys->getLevelList())->with('program', $programme)->with('level', $sys->getLevelList())
                ->with('year', $sys->years());




        }
        else{
            return redirect("/dashboard");
        }
    }





    public function uploadCourse(SystemController $sys,Request $request){

        if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Support'||@\Auth::user()->role == 'Support'|| @\Auth::user()->role == 'Registrar' || @\Auth::user()->department == 'top' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop') {



            return view('courses.uploadCourse');





        }


        else{

            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');

        }


    }

    public function registrationInfo(SystemController $sys,Request $request){

        if ( @\Auth::user()->role == 'Registrar' || @\Auth::user()->department == 'top' || @\Auth::user()->department == 'Examination' || @\Auth::user()->role == 'Admin'  || @\Auth::user()->department == 'Rector' || @\Auth::user()->department == 'Finance' || @\Auth::user()->department == 'Tpmid' || @\Auth::user()->department=='Tptop') {
            $array = $sys->getSemYear();
            $sem = $array[0]->SEMESTER;
            $year = $array[0]->YEAR;
            if ($request->isMethod("get")) {
                $data=  Models\StudentModel::where("REGISTERED",1)->select("PROGRAMMECODE","LEVEL")->orderBy("PROGRAMMECODE")->orderBy("LEVEL")->groupBy("PROGRAMMECODE")->paginate(500);
                return view('courses.registrationReport')
                    ->with('program', $sys->getProgramList())
                    ->with('year', $sys->years())
                    ->with("data",$data)
                    ->with("sem",$sem)
                    ->with("years",$year);
            } else {

            }
        }
        else{
            return redirect("/dashboard");
        }
    }
    // recover deleted grades
    public function gradeRecovery(SystemController $sys,Request $request){

        if ( @\Auth::user()->role== 'Lecturer' || @\Auth::user()->role== 'HOD' ||  @\Auth::user()->fund== '755991'||  @\Auth::user()->fund== '1201610' || @\Auth::user()->fund== '701088') {

            if ($request->isMethod("get")) {

                return view('courses.recoverGrades')->with('year', $sys->years())
                    ->with("course",$sys->getMountedCourseList())
                    ->with("level",$sys->getLevelList())
                    ->with('program', $sys->getProgramList());


            }

        }
        elseif( @\Auth::user()->department== 'Tpmid' || @\Auth::user()->department== 'Tptop' ){
            return view('courses.recoverGrades')->with('year', $sys->years())
                ->with("course",$sys->getCourseList())
                ->with("level",$sys->getLevelList())
                ->with('program', $sys->getProgramList());

        }
        else{
            return redirect("/dashboard");
        }
    }
    public function ProcessGradeRecovery(SystemController $sys,Request $request){

        $this->validate($request, [

            'level'=>'required',
            'program'=>'required',
            'semester'=>'required',
            'year'=>'required',

        ]);

        $level=$request->input("level");
        $course=$request->input("course");
        $program=$request->input("program");
        $year=$request->input("year");
        $semester=$request->input("semester");

        if($course=="type course name here"){
            $query= Models\DeletedGradesModel::where("level",$level)->where("sem",$semester)
                ->where("year",$year)->whereHas('student', function($q)use ($program) {
                    $q->whereHas('programme', function($q)use ($program) {
                        $q->whereIn('PROGRAMMECODE',  array($program));
                    });
                }) ;
        }
        else{
            $query= Models\DeletedGradesModel::where("level",$level)->where("sem",$semester)
                ->where("year",$year)->where("code",$course)->whereHas('student', function($q)use ($program) {
                    $q->whereHas('programme', function($q)use ($program) {
                        $q->whereIn('PROGRAMMECODE',  array($program));
                    });
                }) ;

        }
        $data=$query->get();

        foreach($data as $row){
            $result=new Models\AcademicRecordsModel();
            $result->course=$row->course;
            $result->code=$row->code;
            $result->student=$row->student;
            $result->indexno=$row->indexno;
            $result->credits=$row->credits;
            $result->quiz1=$row->quiz1;
            $result->quiz2=$row->quiz2;
            $result->quiz3=$row->quiz3;
            $result->midSem1=$row->midSem1;
            $result->exam=$row->exam;
            $result->total=$row->total;
            $result->grade=$row->grade;
            $result->gpoint=$row->gpoint;
            $result->year=$row->year;
            $result->sem=$row->sem;
            $result->level=$row->level;
            $result->yrgp=$row->yrgp;
            $result->groups=$row->groups;
            $result->lecturer=$row->lecturer;
            $result->resit=$row->resit;
            $result->dateRegistered=$row->dateRegistered;
            $result->createdAt=$row->createdAt;
            $result->updates=$row->updates;
            $result->save();


        }

        $query->delete();






    }

    public function gradeModification(SystemController $sys,Request $request){

        if ( @\Auth::user()->role== 'Lecturer' || @\Auth::user()->role== 'HOD' ||  @\Auth::user()->fund== '755991'||  @\Auth::user()->fund== '1201610' || @\Auth::user()->fund== '701088') {

            if ($request->isMethod("get")) {

                return view('courses.deleteGrades')->with('year', $sys->years())
                    ->with("course",$sys->getMountedCourseList())
                    ->with("level",$sys->getLevelList())
                    ->with('program', $sys->getProgramList());


            }

        }
        elseif(@\Auth::user()->role=='Admin' || @\Auth::user()->department=='top' || @\Auth::user()->department=='Tptop' ||  @\Auth::user()->department== 'Tpmid' || @\Auth::user()->department== 'Tptop'){
            return view('courses.deleteGrades')->with('year', $sys->years())
                ->with("course",$sys->getMountedCourseList())
                ->with("level",$sys->getLevelList())
                ->with('program', $sys->getProgramList());

        }
        else{
            return redirect("/dashboard");
        }
    }

    public function ProcessGradeModification(SystemController $sys,Request $request){

        //dd($request);
        $this->validate($request, [

            'level'=>'required',
            'program'=>'required',
            'semester'=>'required',

            'year'=>'required',

        ]);

        $level=$request->input("level");
        $course=$request->input("course");
        $program=$request->input("program");
        $year=$request->input("year");
        $indexno=$request->input("indexno");
        $semester=$request->input("semester");

        if($course==""){
            $query= Models\AcademicRecordsModel::where("level",$level)->where("sem",$semester)
                ->where("year",$year)->whereHas('student', function($q)use ($program) {
                    $q->whereHas('programme', function($q)use ($program) {
                        $q->whereIn('PROGRAMMECODE',  array($program));
                    });
                }) ;
        }
        elseif($course==""){
            $query= Models\AcademicRecordsModel::where("level",$level)->where("sem",$semester)
                ->where("year",$year)->where("code",$course)->whereHas('student', function($q)use ($program) {
                    $q->whereHas('programme', function($q)use ($program) {
                        $q->whereIn('PROGRAMMECODE',  array($program));
                    });
                }) ;
            $query->delete();
        }
        elseif($indexno!=""){
            $query= Models\AcademicRecordsModel::where("level",$level)->where("sem",$semester)->where("indexno",$indexno)
                ->where("year",$year)->where("code",$course)->whereHas('student', function($q)use ($program) {
                    $q->whereHas('programme', function($q)use ($program) {
                        $q->whereIn('PROGRAMMECODE',  array($program));
                    });
                }) ;
            $query->delete();


        }
        // $data=$query->get();

//                 foreach($data as $row){
//                        $result=new Models\DeletedGradesModel();
//                        $result->course=$row->course;
//                        $result->code=$row->code;
//                        $result->student=$row->student;
//                        $result->indexno=$row->indexno;
//                        $result->credits=$row->credits;
//                        $result->quiz1=$row->quiz1;
//                        $result->quiz2=$row->quiz2;
//                        $result->quiz3=$row->quiz3;
//                        $result->midSem1=$row->midSem1;
//                        $result->exam=$row->exam;
//                        $result->total=$row->total;
//                        $result->grade=$row->grade;
//                        $result->gpoint=$row->gpoint;
//                        $result->year=$row->year;
//                        $result->sem=$row->sem;
//                        $result->level=$row->level;
//                        $result->yrgp=$row->yrgp;
//                        $result->groups=$row->groups;
//                        $result->lecturer=$row->lecturer;
//                        $result->resit=$row->resit;
//                        $result->dateRegistered=$row->dateRegistered;
//                       $result->createdAt=$row->createdAt;
//                      $result->updates=$row->updates;
//                        $result->save();
//                       
//                            
//                        }





        return redirect()->back()->with("success","Grades deleted successfully");
    }















    public function transcript(SystemController $sys,Request $request){

        if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Lecturer' || @\Auth::user()->role == 'Registrar' || @\Auth::user()->department == 'top' || @\Auth::user()->department == 'Rector' || @\Auth::user()->department == 'Tpmid' || @\Auth::user()->department == 'Tptop' || @\Auth::user()->role == 'Admin') {

            if ($request->isMethod("get")) {

                return view('courses.showTranscript');

            }
            else{

                $student=  explode(',',$request->input('q'));
                $student=$student[0];



                $sql=Models\StudentModel::where("INDEXNO",$student)->first();


                if(count($sql)==0){

                    return redirect("/transcript")->with("error","<span style='font-weight:bold;font-size:13px;'> $request->input('q') does not exist!</span>");
                }
                else{

                    $array=$sys->getSemYear();
                    $sem=$array[0]->SEMESTER;
                    $year=$array[0]->YEAR;


                    $data=$this->transcriptHeader($sql, $sys)  ;
                    $record=$this->generateTranscript($sql->ID,$sys);
                    return view("courses.transcript")->with('grade',$record)->with("student",$data);




                }
            }

        }

    }
    public function transcriptHeader($student, SystemController $sys) {
        ?>
        <div class="md-card" style="overflow-x: auto;" >

            <div   class="uk-grid" data-uk-grid-margin>

                <table  border="0" width="886px" cellspacing="0" align="center" style="margin-left:8px">

                    <tr>
                        <th height="41" valign="top" class="bod" scope="row">
                            <table width="100%" border="0">
                                <tr>
                                    <th align="center" valign="middle" scope="row">
                                        <table height="133" border="0">
                                            <tr>
                                                <th align="center" valign="middle" scope="row">

                                                    <table border="0" >
                                                        <tr>

                                                            <td>
                                                                <table>
                                                                    <tr>
                                                                        <td class="uk-text-danger uk-text-left" colspan="3"><blinks>Use Mozilla Firefox or Google Chrome. Contact your HOD or call 0246091283 / 0505284060 for any assistance. </blinks></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td colspan="3" align='left'> <img src="<?php echo url('public/assets/img/academic.jpg')?>" style='width: 826px;height: auto;margin-bottom: 10px;'/></td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td class="uk-text-bold"style="padding-right: px;">INDEX NUMBER</td> <td style=""><?php echo $student->INDEXNO;?></td>
                                                                        <td rowspan="5" width="145">&nbsp;
                                                                            <img   style="width:130px;height: auto;margin-left: 8px"
                                                                                <?php
                                                                                $pic = $student->INDEXNO;
                                                                                ?>
                                                                                   src='<?php echo url("public/albums/students/$pic.JPG")?>' alt="  Affix student picture here"    />
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="uk-text-bold" style="">NAME</td> <td><?php echo strtoupper($student->TITLE .' '.  $student->NAME)?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="uk-text-bold"style="">GENDER</td> <td><?php echo strtoupper($student->SEX)?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="uk-text-bold">PROGRAMME</td> <td><?php echo strtoupper($student->program->PROGRAMME)?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="uk-text-bold" style="">DATE OF ADMISSION</td> <td><?php echo strtoupper($student->DATE_ADMITTED)?></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="uk-text-bold" style="">DATE OF BIRTH</td> <td><?PHP echo  $student->DATEOFBIRTH ; ?></td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td class="uk-text-left" colspan="3">&nbsp;<br/>For HND only. &nbsp;&nbsp;Grade &nbsp;= &nbsp;Value, &nbsp;&nbsp;&nbsp;A+ &nbsp;= &nbsp;5.0, &nbsp;&nbsp;&nbsp;A &nbsp;= &nbsp;4.5, &nbsp;&nbsp;&nbsp;B+ &nbsp;= &nbsp;4.0, &nbsp;&nbsp;&nbsp;B &nbsp;= &nbsp;3.5, &nbsp;&nbsp;&nbsp;C+ &nbsp;= &nbsp;3, &nbsp;&nbsp;&nbsp;C &nbsp;= &nbsp;2.5, &nbsp;&nbsp;&nbsp;D+ &nbsp;= &nbsp;2, &nbsp;&nbsp;&nbsp;D &nbsp;= &nbsp;1.5, &nbsp;&nbsp;&nbsp;F &nbsp;= &nbsp;0,  <br/>&nbsp;&nbsp; red asterisk means resited</td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                            </tr>
                                        </table> <!-- end basic infos -->





            </div>

        </div>
        </tr>
        </table></th>
        </tr>
        <tr></tr>
        </table>

        <?php

    }
    public function generateTranscript($sql,  SystemController $sys){

        $records=  Models\AcademicRecordsModel::where("student",$sql)->groupBy("year")->groupBy("level")->orderBy("level")->get();


        ?>


        <table width='700px' style="text-align:left; margin-top:-2px; font-size: 16px" height="90" class=""  border="0">
            <tr>

                <td  style=" " align="left">
                    <?php
                    $gpoint=0.0;
                    $totcredit=0;
                    $totgpoint=0.0;
                    $gcredit=0;
                    $b=0.0;
                    $a=0;
                    foreach ($records as $row){
                        for($i=1;$i<3;$i++){
                            $query=  Models\AcademicRecordsModel::where("student",$sql)->where("year",$row->year)->where("sem",$i)->get()->toArray();


                            if(count($query)>0){


                                echo "<div class='uk-text-bold' align='left' style='margin-left:18px'>YEAR : ".$row->year."    ";
                                echo ", SEMESTER : ".$i;
                                echo ", LEVEL :  " .$row->level." <hr/></div>";






                                ?>

                                <div class="uk-overflow-container">
                                <table style="margin-left:18px"  border="0" style="" width='840px'  class="uk-table uk-table-striped">
                                    <thead >
                                    <tr class="uk-text-bold" style="background-color:#1A337E;color:white;">
                                        <td  width="86">CODE</td>
                                        <td  width="458">COURSE</td>
                                        <td align='center' width="48">CR</td>
                                        <td align='center' width="49">GD</td>
                                        <td align='center'width="95" >GP</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    foreach ($query as $rs){


                                    if($rs['grade']!="IC" and $rs['grade']!="E" and $rs['grade']!="NC"){

                                    ?>
                                    <tr>
                                        <td <?php // if($rs['grade']=="E"|| $rs['grade']=="F"){ echo "style='display:none'";}?>> <?php $object=$sys->getCourseByCodeObject($rs['code']); echo @$object[0]->COURSE_CODE; ?></td>
                                        <td <?php // if($rs['grade']=="E"|| $rs['grade']=="F"){ echo "style='display:none'";}?>> <?php
                                            if($rs['resit']=="yes"){
                                                echo @$object[0]->COURSE_NAME."<span style='color:red'>*</span>";}else{echo @$object[0]->COURSE_NAME;}?> </td>

                                        <td align='center' <?php // if($rs['grade']=="E"|| $rs['grade']=="F"){ echo "style='display:none'";}?>><?php  @$gcredit+=@$rs['credits'];   $totcredit+=@$rs['credits'];@$a+=$totcredit; if($rs['credits']){ echo $rs['credits'];} else{echo "IC";};?></td>

                                        <td align='center' <?php // if($rs['grade']=="E" || $rs['grade']=="F"){ echo "style='display:none'";}?>><?php  if($rs['grade']){ echo @$rs['grade'];} else{echo "IC";}?></td>


                                        <td align='center' <?php // if($rs['grade']=="E"|| $rs['grade']=="F"){ echo "style='display:none'";}?>>
                                            <?php   @$gpoint+=@$rs['gpoint']; @$totgpoint+=@$rs['gpoint'];@$b+=@$totgpoint;if($rs['gpoint']){ echo $rs['gpoint'];} else{echo "0";}  ?></td>



                                        <?php
                                        }
                                        }?>
                                    </tr>
                                    <tr>

                                        <td>&nbsp</td>

                                        <td class="uk-text-bold"><span>GPA</span> <?php echo  number_format(@($gpoint/$gcredit), 2, '.', ',');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>

                                        <td class="uk-text-bold" align='center'><?php echo $gcredit; ?></td>
                                        <td >&nbsp;</td>
                                        <td class="uk-text-bold" align='center'><?php echo $gpoint; ?>&nbsp;</td>
                                    </tr>
                                    <tr>

                                        <td>&nbsp</td>

                                        <td class="uk-text-bold"><span>CGPA</span> <?php echo  number_format(@($totgpoint/$totcredit), 2, '.', ',');?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>

                                        <td class="uk-text-bold" align='center'><?php echo   $totcredit; ?></td>
                                        <td >&nbsp;</td>
                                        <td class="uk-text-bold" align='center'><?php echo $totgpoint;   $b="";$a=""; ?>&nbsp;</td>
                                    </tr>

                                    </tbody>

                                    <?php
                                    $gpoint=0.0;
                                    $gcredit=0;
                                    ?>
                                </table>
                            <?php }else{
                                echo "<p class='uk-text-danger'>No results to display</p>";
                                ?><?php }?>
                            <p>&nbsp;</p>
                            </div><?php }  }

                    ?>


            </tr>

        </table>

        </div></div>

    <?php }

    /**
     * Display a list of all of the user's task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request,SystemController $sys)
    {
        if($request->user()->isSupperAdmin  || @\Auth::user()->department=="top" || @\Auth::user()->role=="Admin" || @\Auth::user()->department=="Rector" || @\Auth::user()->department=="Tpmid" || @\Auth::user()->department=="Tptop"){

            $courses= Models\CourseModel::query() ;
        }
        elseif(@\Auth::user()->role=="HOD" || @\Auth::user()->role=="Support" || @\Auth::user()->role=="Registrar") {
            $courses = Models\CourseModel::where('PROGRAMME', '!=', '')->whereHas('programs', function($q) {
                $q->whereHas('departments', function($q) {
                    $q->whereIn('DEPTCODE', array(@\Auth::user()->department));
                });
            }) ;
        }

        if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $courses->where($request->input('by'), "LIKE", "%" . $request->input("search", "") . "%");
        }
        if ($request->has('program') && trim($request->input('program')) != "") {
            $courses->where("PROGRAMME", $request->input("program", ""));
        }
        if ($request->has('level') && trim($request->input('level')) != "") {
            $courses->where("COURSE_LEVEL", $request->input("level", ""));
        }
        if ($request->has('semester') && trim($request->input('semester')) != "") {
            $courses->where("COURSE_SEMESTER", "=", $request->input("semester", ""));
        }


        $data = $courses->groupBy('COURSE_NAME')->paginate(100);

        $request->flashExcept("_token");


        return view('courses.index')->with("data", $data)->with('level', $sys->getLevelList())
            ->with('program', $sys->getProgramList());

    }
    public function viewMounted(Request $request,SystemController $sys) {
        $hod=@\Auth::user()->fund;

//      if(@\Auth::user()->department=="top"){
//           $courses= Models\MountedCourseModel::query() ;
//      }
//      elseif(@\Auth::user()->role=="Lecturer"){
//          $courses= Models\MountedCourseModel::query()->where('LECTURER',@\Auth::user()->fund) ;
//      }
//
//      else{
//          $courses= Models\MountedCourseModel::query()->where('MOUNTED_BY',$hod) ;
//      }

        if($request->user()->isSupperAdmin  ||  @\Auth::user()->department=="top" || @\Auth::user()->role=="Admin"){

            $courses= Models\MountedCourseModel::query() ;
        }
        elseif(@\Auth::user()->role=="HOD" || @\Auth::user()->role=="Support" || @\Auth::user()->role=="Registrar") {
            $courses =Models\MountedCourseModel::where('COURSE', '!=', '')->whereHas('courses', function($q) {
                $q->whereHas('programs', function($q) {
                    $q->whereIn('DEPTCODE', array(@\Auth::user()->department));
                });
            }) ;
        }



        if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $courses->where($request->input('by'), "LIKE", "%" . $request->input("search", "") . "%");
        }
        if ($request->has('program') && trim($request->input('program')) != "") {
            $courses->where("PROGRAMME", $request->input("program", ""));
        }
        if ($request->has('level') && trim($request->input('level')) != "") {
            $courses->where("COURSE_LEVEL", $request->input("level", ""));
        }
        if ($request->has('semester') && trim($request->input('semester')) != "") {
            $courses->where("COURSE_SEMESTER", "=", $request->input("semester", ""));
        }
        if ($request->has('year') && trim($request->input('year')) != "") {
            $courses->where("COURSE_YEAR", "=", $request->input("year", ""));
        }


        $data = $courses->paginate(100);

        $request->flashExcept("_token");


        return view('courses.view_mounted')->with("data", $data)
            ->with('program', $sys->getProgramList())
            ->with('level', $sys->getLevelList())
            ->with('year',$sys->years());
    }
    public function viewRegistered(Request $request,SystemController $sys , User $user, Models\AcademicRecordsModel $record) {

        //$this->authorize('update',$record); // in Controllers
        /*if(Gate::allows('updatesss',$record)){
            abort(403,"No authorization");
        }*/
        $array = $sys->getSemYear();
        $sem = $array[0]->SEMESTER;
        $year = $array[0]->YEAR;
        $person=@\Auth::user()->fund;
        $lecturer=@\Auth::user()->fund;

        // dd($request->user()->isSupperAdmin);
        if(@\Auth::user()->role=='Lecturer' || @\Auth::user()->role=='HOD' ||@\Auth::user()->role=='Dean'){


            /*
             * make sure that only courses mounted for a
             * lecturer is available to him
             */

            $courses= Models\AcademicRecordsModel::query()->where('lecturer', $person) ;


        }
        elseif($request->user()->isSupperAdmin){

            $courses= Models\AcademicRecordsModel::query()->where("year".$year)
                ->where("sem",$sem);

        }
        else{
            //abort(420, "Illegal access detected");
            return response('Unauthorized.', 401);
        }
        if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $courses->where('course',   $sys->getCourseByIDCode($request->input("search", "")));

        }

        if ($request->has('level') && trim($request->input('level')) != "") {
            $courses->where("level", $request->input("level", ""));
        }
        if ($request->has('semester') && trim($request->input('semester')) != "") {
            $courses->where("sem", "=", $request->input("semester", ""));
        }
        if ($request->has('year') && trim($request->input('year')) != "") {
            $courses->where("year", "=", $request->input("year", ""));
        }
        $data = $courses->groupby('course')->paginate(100);

        $request->flashExcept("_token");

        foreach ($data as $key => $row) {

            $arr=$sys->getCourseCodeByID($row->code);
            // dd($arr);
            $data[$key]->CODE=$arr;

            $total=$sys->totalRegistered($sem,$year,$row->course,$row->level, $lecturer);
            $data[$key]->REGISTERED=$total;
        }




        return view('courses.registered_courses')->with("data", $data)
            ->with('program', $sys->getProgramList())
            ->with('level', $sys->getLevelList())
            ->with('year',$sys->years());


    }
    public function mountCourse(SystemController $sys) {
        if(@\Auth::user()->role=='HOD' || @\Auth::user()->role == 'Admin'|| @\Auth::user()->role=='Support' || @\Auth::user()->role=='Registrar'){
            $programme=$sys->getProgramList();

            $course=$sys->getCourseList();
            //$lecturer=$sys->getLectureList();
            $allLectureres=$sys->getLectureList_All();
            // $totalLecturers = array_merge( $lecturer, $allLectureres);
            return view('courses.mount')->with('program', $programme)
                ->with('course', $course)
                ->with('level', $sys->getLevelList())
                ->with('lecturer',$allLectureres);
        }
        else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }

    public function create(SystemController $sys) {
        if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='top' || @\Auth::user()->department=='Tptop' || @\Auth::user()->role == 'Admin'){
            $programme=$sys->getProgramList();
            return view('courses.create')->with('level', $sys->getLevelList())->with('programme', $programme);
        }
        else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    public function show(Request $request) {

    }
    /**
     * Create a new task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop'|| @\Auth::user()->department=='top' || @\Auth::user()->department=='Tptop' || @\Auth::user()->role == 'Admin'){

            $this->validate($request, [
                'name' => 'required',
                'program' => 'required',
                'code' => 'required',
                'level' => 'required',
                'credit' => 'required',
                'semester' => 'required'
            ]);

            $user=@\Auth::user()->id;

            $name = strtoupper($request->input('name'));
            $program = strtoupper($request->input('program'));
            $level =strtoupper( $request->input('level'));
            $semester =strtoupper( $request->input('semester'));
            $credit = strtoupper($request->input('credit'));
            $code = strtoupper($request->input('code'));

            $course = new Models\CourseModel();
            $course->COURSE_NAME = $name;
            $course->COURSE_CREDIT = $credit;
            $course->PROGRAMME = $program;
            $course->COURSE_SEMESTER = $semester;
            $course->COURSE_CODE = $code;
            $course->COURSE_LEVEL = $level;
            $course->USER = $user;


            if ($course->save()) {
                //\DB::commit();
                return redirect("/courses")->with("success", "Following Courses:<span style='font-weight:bold;font-size:13px;'> $name added </span>successfully added! ");
            } else {

                return redirect("/courses")->withErrors("Following Courses N<u>o</u> :<span style='font-weight:bold;font-size:13px;'> $name could not be added </span>could not be added!");
            }

        }
        else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    public function mountCourseStore(Request $request, SystemController $sys) {
        if(@\Auth::user()->role=='HOD' || @\Auth::user()->role=='Support' || @\Auth::user()->role=='Registrar' || @\Auth::user()->role == 'Admin'){
            \DB::beginTransaction();
            try {
                $this->validate($request, [
                    'course' => 'required',
                    'program' => 'required',

                    'level' => 'required',
                    'credit' => 'required',
                    'semester' => 'required',

                    'year' => 'required'
                ]);


                $course = $request->input('course');
                $program = $request->input('program');
                $level = $request->input('level');
                $semester = $request->input('semester');
                $credit = $request->input('credit');
                $year = "2017/2018";
                $lecturer = $request->input('lecturer');
                $type = $request->input('type');
                if($request->input('type')==""){
                    $type="Core";
                }
                else{
                    $type = $request->input('type');
                }
                $hod = @\Auth::user()->fund;
                $courseDetail=$sys->getCourseByCode($course);
                $mountedCourse = new Models\MountedCourseModel();
                $mountedCourse->COURSE = $sys->getCourseByCode($course);
                $mountedCourse->COURSE_CODE = $course;
                $mountedCourse->COURSE_CREDIT = $credit;
                $mountedCourse->COURSE_SEMESTER = $semester;
                $mountedCourse->COURSE_LEVEL = $level;
                $mountedCourse->COURSE_TYPE = $type;
                $mountedCourse->PROGRAMME = $program;
                $mountedCourse->LECTURER = $lecturer;
                $mountedCourse->COURSE_YEAR = $year;
                $mountedCourse->MOUNTED_BY = $hod;
                $mountedCourse->save();
                // REPEAT SAME FOR EVENING




                if ($mountedCourse->save()) {
                    \DB::commit();
//                $CourseArray=$sys->getCourseCodeByIDArray($course);
//                $courseName=$CourseArray[0]->COURSE_NAME;
//                $courseCode=$CourseArray[0]->COURSE_CODE;
//                $staffArray=$sys->getLecturer($lecturer);
//                $lecturerName=$staffArray[0]->fullName;
//                $lecturePhone=$staffArray[0]->phone;
//                $lectureStaffID=$staffArray[0]->staffID;
//                $programCode=$sys->getProgram($program);
//                $message="Hi, $lecturerName, you have been assigned $courseName, $courseCode, $programCode, year $level, $year, sem $semester";
//                //dd($message);
                    // $sys->firesms($message, $lecturePhone,$lectureStaffID );
                    return redirect("/mounted_view")->with("success", "well done:<span style='font-weight:bold;font-size:13px;'> course mounted</span>successfully  ");
                } else {

                    return redirect("/mounted_view")->withErrors("Whoops N<u>o</u> :<span style='font-weight:bold;font-size:13px;'> course could not be mounted </span>could not be added!");
                }
            } catch (\Exception $e) {
                \DB::rollback();
            }
        }
        else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    public function enterMark($course,$code, SystemController $sys ,Models\AcademicRecordsModel $record ){
        //$this->authorize('update',$record); // in Controllers
        if(@\Auth::user()->role=='HOD' ||@\Auth::user()->role=='Lecturer' || @\Auth::user()->department=='Tpmid'  ){
            $array=$sys->getSemYear();
            $sem=$array[0]->SEMESTER;
            $year=$array[0]->YEAR;

            $lecturer=@\Auth::user()->fund;
            $group=  @explode(',', @\Auth::user()->student_groups);

            $resultOpen=$array[0]->ENTER_RESULT;
            if($resultOpen==1){
                $mark = Models\AcademicRecordsModel::where('code',$code)

                    ->where('lecturer',$lecturer)
                    ->where('year',$year)
                    ->where('sem',$sem)
                    //  ->orwhereIn('groups',$group)
                    ->paginate(70);
                $total=count($mark);
                $th=$sys->getCourseCodeByIDArray2($code);
                $courseName=$th[0]->COURSE_NAME;
                return view('courses.markSheet')->with('mark', $mark)
                    ->with('year', $year)
                    ->with('sem', $sem)
                    ->with('mycode', $code)
                    ->with('course', $courseName)
                    ->with('years',$sys->years())
                    ->with('total', $total);
            }
            else{
                abort(434, "{!!<b>Entering of marks has ended contact the Dean of your School</b>!!}");
                redirect("/registered_courses");

            }
        }
        else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');


        }
    }
    public function marksDownloadExcel( $code, SystemController $sys )

    {

        $array=$sys->getSemYear();
        $sem=$array[0]->SEMESTER;
        $year=$array[0]->YEAR;

        $lecturer=@\Auth::user()->fund;

        $data=Models\AcademicRecordsModel::
        join('tpoly_students', 'tpoly_academic_record.indexno', '=', 'tpoly_students.INDEXNO')
            ->where('tpoly_academic_record.code',$code)
            ->where('tpoly_academic_record.lecturer',$lecturer)
            ->where('tpoly_academic_record.year',$year)
            ->where('tpoly_academic_record.sem',$sem)
            ->select('tpoly_students.INDEXNO','tpoly_students.NAME','tpoly_academic_record.quiz1','tpoly_academic_record.quiz2','tpoly_academic_record.midsem1','tpoly_academic_record.exam')
            ->orderBy("tpoly_students.INDEXNO")
            ->get();

        return Excel::create('itsolutionstuff_example', function($excel) use ($data) {

            $excel->sheet('mySheet', function($sheet) use ($data)

            {

                $sheet->fromArray($data);

            });

        })->download('xlsx');


    }

    public function downloadRegisteredExcel(Request $request, SystemController $sys )

    {

        $this->validate($request, [


            'course' => 'required',
            'sem' => 'required',
            'year' => 'required',
            'level' => 'required',
        ]);


        $array = $sys->getSemYear();
        $sem = $request->input("sem");
        $year = $request->input("year");
        $level = $request->input("level");
        $course = $request->input("course");
        $lecturer = @\Auth::user()->fund;

        $data = Models\AcademicRecordsModel::
        join('tpoly_students', 'tpoly_academic_record.student', '=', 'tpoly_students.ID')
            ->where('tpoly_academic_record.code', $course)
            ->where('tpoly_academic_record.lecturer', $lecturer)
            ->where('tpoly_academic_record.year', $year)
            ->where('tpoly_academic_record.sem', $sem)
            ->select('tpoly_students.INDEXNO', 'tpoly_students.NAME', 'tpoly_academic_record.quiz1', 'tpoly_academic_record.quiz2', 'tpoly_academic_record.midsem1', 'tpoly_academic_record.exam', 'tpoly_academic_record.total')
            ->orderBy("tpoly_students.INDEXNO")
            ->get();

        return Excel::create($course, function ($excel) use ($data,$course){

            $excel->sheet($course, function ($sheet) use ($data) {

                $sheet->fromArray($data);

            });

        })->download('xls');


    }
    public function processMark(SystemController $sys,Request $request) {
        // dd($request);
        set_time_limit(36000);
        ini_set('max_input_vars', '9000');
        $array = $sys->getSemYear();
        $sem =$request->input('sem');
        $year = $request->input('years');
        \Session::put('year', $year);
        \Session::put('sem', $sem);
        $resultOpen = $array[0]->ENTER_RESULT;
        $lecturer= @\Auth::user()->fund;
        $year=\Session::get('year');
        $sem=\Session::get('sem');
        if(empty($sem)){
            $sem = $array[0]->SEMESTER;
        }
        if(empty($year)){
            $year = $array[0]->YEAR;
        }

        if ($resultOpen == 1) {

            //set_time_limit(36000);
            // ini_set('max_input_vars', '9000');
            $host = $_SERVER['HTTP_HOST'];
            $ipAddr = $_SERVER['REMOTE_ADDR'];
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $studentIndexNo = $sys->getStudentByID($request->input('student'));
            //dd(\Auth::user()->staffID);
            $upper = count($request->input('student')) ;
            $key = $request->input('key');
            $student = $request->input('student');
            $quiz1 = $request->input('quiz1');
            $quiz2 = $request->input('quiz2');
            $quiz3 = $request->input('quiz3');
            $midsem1 = $request->input('midsem1');

            $course = $request->input('course');
            $exam = $request->input('exam');

            $courseArr= $sys->getCourseMountedInfo($course);
            //  dd($request);
            // dd($request->input('counter') );

            $quiz1Old = $request->input('quiz1Old');
            $quiz2Old = $request->input('quiz2Old');
            $quiz3Old = $request->input('quiz3Old');
            $midsem1Old = $request->input('midsemOld');
            $examOld = $request->input('examOld');
            for ($i = 0; $i < $upper; $i++) {
                $keyData = $key[$i];
                $studentData = $student[$i];
                $quiz1Data = $quiz1[$i];
                $quiz2Data = $quiz2[$i];
                $quiz3Data = $quiz3[$i];
                $midsem1Data = $midsem1[$i];
                $examData = $exam[$i];
                // for logging
                $quiz1OldData = $quiz1Old[$i];
                $quiz2OldData = $quiz2Old[$i];
                $quiz3OldData = $quiz3Old[$i];
                $midsem1OldData = $midsem1Old[$i];
                $examOldData = $examOld[$i];
                $fortyPercent = $quiz1Data + $quiz2Data + $quiz3Data + $midsem1Data;
                $examTotal = $examData;
                $total = $fortyPercent + $examTotal;

                $OldfortyPercent = $quiz1OldData + $quiz2OldData + $quiz3OldData + $midsem1OldData;
                $oldExam = $examOldData;
                $oldClassScore = $OldfortyPercent;

                $examLog = new Models\GradeLogModel();
                $examLog->actor = $lecturer;
                $examLog->student = $studentData;
                $examLog->course = $course;
                $examLog->oldClassScore = $oldClassScore;
                $examLog->newClassScore = $fortyPercent;
                $examLog->oldExamScore = $oldExam;
                $examLog->newExamScore = $examTotal;
                $examLog->ip = $ipAddr;
                $examLog->host = $host;
                $examLog->userAgent = $userAgent;
                if ($examLog->save()) {

                    $programme=$sys->getCourseProgrammeMounted($course);
                    // dd($total);
                    $program=$sys->getProgramArray($programme);

                    $gradeArray = @$sys->getGrade($total, $program[0]->GRADING_SYSTEM);
                    $grade = @$gradeArray[0]->grade;

                    //dd($gradeArray );
                    $gradePoint = round(($courseArr[0]->COURSE_CREDIT*@$gradeArray[0]->value),2);
                    $cgpa= number_format(@( $gradePoint/$courseArr[0]->COURSE_CREDIT), 2, '.', ',');
                    $oldCgpa= @Models\StudentModel::where("INDEXNO",$student)->select("CGPA","CLASS")->first();

                    $newCgpa=$cgpa+@$oldCgpa->CGPA;
                    $class=@$sys->getClass($newCgpa);
                    Models\StudentModel::where("INDEXNO",$student)->update(array("CGPA"=>$newCgpa,"CLASS"=>$class));

                    Models\AcademicRecordsModel::where("id", $keyData)->where('lecturer', $lecturer)->update(array("quiz1" => $quiz1Data, "quiz2" => $quiz2Data, "quiz3" => $quiz3Data, "midSem1" => $midsem1Data, "exam" => $examTotal, "total" => $total, "lecturer" => $lecturer, 'grade' => $grade, 'gpoint' => $gradePoint));


                }
            }
            return redirect()->back();
        }
    }
    public function storeMark($course,$code, SystemController $sys,Request $request){

        if (@\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Lecturer' || @\Auth::user()->department == 'Tpmid') {
            $array = $sys->getSemYear();
            $sem =$request->input('sem');
            $year = $request->input('years');
            \Session::put('year', $year);
            \Session::put('sem', $sem);
            $resultOpen = $array[0]->ENTER_RESULT;
            $lecturer= @\Auth::user()->fund;
            $year=\Session::get('year');
            $sem=\Session::get('sem');
            if(empty($sem)){
                $sem = $array[0]->SEMESTER;
            }
            if(empty($year)){
                $year = $array[0]->YEAR;
            }


            $mark = @Models\AcademicRecordsModels::query()->where("code", $course)->where('lecturer', $lecturer);

            if ($request->has('years') && trim($request->input('years')) != "") {
                $mark->where("year", "=", $request->input("years", ""));
            }
            if ($request->has('sem') && trim($request->input('sem')) != "") {
                $mark->where("sem", "=", $request->input("sem", ""));
            }
            $request->flashExcept("_token");
            $total = @count($mark->get());
            // dd($mark);
            $courseName = @$sys->getCourse($courseArr[0]->COURSE);
            $data=$mark->paginate(70);

            return view('courses.markSheet')->with('mark', $data)
                ->with('year', $year)
                ->with('sem', $sem)
                ->with('years', $sys->years())
                ->with('mycode', $code)
                ->with('course', $courseName)
                ->with('total', $total);}


    }
    public function attendanceSheet(Request $request,SystemController $sys){
        if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='top' || @\Auth::user()->department=='Tptop'|| @\Auth::user()->role=='Dean' || @\Auth::user()->role=='Lecturer' || @\Auth::user()->department=='Rector' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop'){
            if ($request->isMethod("get")) {
                $course=$sys->getMountedCourseList();

                return view('courses.attendanceSheet')
                    ->with('courses',$course)->with('year',$sys->years())->with('level', $sys->getLevelList());
            }
            else{

                $semester = $request->input('semester');
                $year = $request->input('year');
                $course =  $request->input('course') ;
                $level = $request->input('level');

                $mark = Models\AcademicRecordsModel::where("code", $course)->where('year',$year)->where('sem',$semester)->where('level',$level)->paginate(100);
                // dd($mark);
                $courseArr= $sys->getCourseMountedInfo($course);
                // dd($courseArr);
                $courseDb= $courseArr[0]->ID;
                $courseCreditDb= $courseArr[0]->COURSE_CREDIT;
                $courseLecturerDb= $courseArr[0]->LECTURER;
                $courseName=@$sys->getCourseCodeByIDArray($courseArr[0]->COURSE);
                $displayCourse=$courseName[0]->COURSE_NAME;
                $displayCode=$courseName[0]->COURSE_CODE;
                \Session::put('year', $year);
                $url = url('printAttendance/'.$semester.'/sem/'.$displayCourse.'/course/'.$displayCode.'/code/'.$level.'/level/'.$course.'/id');

                $print_window = "<script >window.open('$url','','location=1,status=1,menubar=yes,scrollbars=yes,resizable=yes,width=1000,height=500')</script>";
                $request->session()->flash("success",
                    "    $print_window");
                return redirect("/attendanceSheet");

                // return view('courses.printAttendance')->with('mark', $mark)
                //     ->with('year', $year)
                //     ->with('sem', $semester)
                //     ->with('course', $displayCourse)
                //     ->with('code', $displayCode);


            }
        }
        else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    public function printAttendance(Request $request,$semester,$course,$code,$level,$id) {
        $year=\Session::get('year');
        $mark = Models\AcademicRecordsModel::where("code", $code)->where('year',$year)->where('sem',$semester)->where('level',$level)->paginate(100);

        return view('courses.printAttendance')->with('mark', $mark)
            ->with('year', $year)
            ->with('sem', $semester)
            ->with('course', $course)
            ->with('code', $code);


    }
    public function showFileUpload(SystemController $sys){
        if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop'|| @\Auth::user()->role=='Dean' || @\Auth::user()->role=='Lecturer'){
            $programme=$sys->getProgramList();
            $course=$sys->getMountedCourseList();

            return view('courses.markUpload')->with('programme', $programme)
                ->with('courses',$course)->with('level', $sys->getLevelList())->with('year',$sys->years());
        }
        else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    public function showFileUploadRegistered(SystemController $sys){
        if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop'|| @\Auth::user()->role=='Dean' || @\Auth::user()->role=='Lecturer'){
            $programme=$sys->getProgramList();
            $course=$sys->getMountedCourseList();

            return view('courses.downloadRegistered')->with('programme', $programme)
                ->with('courses',$course)->with('level', $sys->getLevelList())->with('year',$sys->years());
        }
        else{
            throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
        }
    }
    /*
     * Uploading old academic records here
     * file format Excel
     */
    public function uploadLegacy(Request $request, SystemController $sys){

        if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='top' || @\Auth::user()->department=='Tptop'|| @\Auth::user()->role=='Dean' || @\Auth::user()->role=='Support' || @\Auth::user()->role=='Registrar' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop'){
            if ($request->isMethod("get")) {

                $programme = $sys->getProgramList();
                $course = $sys->getCourseList();

                return view('courses.legacyGrades')->with('level', $sys->getLevelList())->with('program', $programme)->with('level', $sys->getLevelList())
                    ->with('course', $course)->with('year', $sys->years());
            }
            else{
                $this->validate($request, [

                    'file' => 'required',
                    'course' => 'required',
                    'sem' => 'required',
                    'year' => 'required',
                    'credit' => 'required',
                    'program' => 'required',
                    'level' => 'required',
                ]);



                $valid_exts = array('csv', 'xls', 'xlsx'); // valid extensions
                $file = $request->file('file');
                $path = $request->file('file')->getRealPath();

                $ext = strtolower($file->getClientOriginalExtension());

                $semester = $request->input('sem');
                $year = $request->input('year');
                $course =  $request->input('course') ;
                $programme = $request->input('program');
                $level = $request->input('level');
                $credit=$request->input('credit');
                //$studentIndexNo = $sys->getStudentIDfromIndexno($request->input('student'));


                if (in_array($ext, $valid_exts)) {

                    $data = Excel::load($path, function($reader) {

                    })->get();
                    if (!empty($data) && $data->count()) {


                        foreach ($data as $key => $value) {

                            $totalRecords = count($data);



                            $studentID= $sys->getStudentIDfromIndexno($value->indexno);
                            $studentDb= $value->indexno  ;

                            //$courseArr= $sys->getCourseMountedInfo($course);
                            // dd($courseArr);
                            //$courseDb= $courseArr[0]->ID;
                            //$courseCreditDb= $courseArr[0]->COURSE_CREDIT;
                            //$courseLecturerDb= $courseArr[0]->LECTURER;
                            $courseName=$sys->getCourseCodeByIDArray($course);
                            $displayCourse=$courseName[0]->COURSE_NAME;
                            $displayCode=$courseName[0]->COURSE_CODE;
                            $studentSearch = $sys->studentSearchByIndexNo($programme); // check if the students in the file tally with registered students
                            //dd($studentDb);
                            if (@in_array($studentDb, $studentSearch)) {
                                $indexNo=$value->indexno;
                                $quiz1=$value->quiz1;
                                $quiz2=$value->quiz2;
                                $midsem=$value->midsem1;
                                $exam=$value->exam;
                                $total= round(($quiz2+$quiz1+$midsem+$exam),2);
                                $programmeDetail=$sys->getCourseProgramme($course);

                                $program=$sys->getProgramArray($programmeDetail);
                                $gradeArray = @$sys->getGrade($total, $program[0]->GRADING_SYSTEM);
                                $grade = @$gradeArray[0]->grade;

                                // dd($gradeArray );
                                $gradePoint = @$gradeArray[0]->value;
                                $test=Models\AcademicRecordsModel::where("indexno",$indexNo)->where("level",$level)->where("sem",$semester)->where("course",$course)->where("credits",$credit)->where("year",$year)->get()->toArray();
                                if(empty($test)){
                                    $record = new Models\AcademicRecordsModel();
                                    $record->indexno = $indexNo;
                                    $record->course = $course;
                                    $record->sem = $semester;
                                    $record->year = $year;
                                    $record->credits = $credit;
                                    $record->student= $studentID;
                                    $record->level = $level;
                                    $record->quiz1 = $quiz1;
                                    $record->quiz2 = $quiz2;
                                    $record->quiz3 = 0;
                                    $record->midSem1 = $midsem;
                                    $record->exam = $exam;
                                    $record->total = $total;
                                    $record->lecturer = @\Auth::user()->fund;
                                    $record->grade = $grade;
                                    $record->gpoint =round(( $credit*$gradePoint),2);
                                    $record->save();

                                    $cgpa= number_format(@(( $credit*$gradePoint)/$credit), 2, '.', ',');
                                    $oldCgpa= @Models\StudentModel::where("INDEXNO",$indexNo)->select("CGPA","CLASS")->first();

                                    $newCgpa=$cgpa+@$oldCgpa->CGPA;
                                    $class=@$sys->getClass($newCgpa);
                                    Models\StudentModel::where("INDEXNO",$indexNo)->update(array("CGPA"=>$newCgpa,"CLASS"=>$class));
                                    \DB::commit();

                                }
                                else{
                                    Models\AcademicRecordsModel::where("indexno",$indexNo)->where("level",$level)->where("sem",$semester)->where("course",$course)->where("credits",$credit)->where("year",$year)->update(
                                        array(
                                            "indexno" =>$indexNo,
                                            "course"=>$course,
                                            "sem" =>$semester,
                                            "year"=>$year,
                                            "credits"=>$credit,
                                            "student"=>$studentID,
                                            "level"=>$level,
                                            "quiz1"=>$quiz1,
                                            "quiz2" =>$quiz2,
                                            "quiz3"=>0,
                                            "midSem1"=>$midsem,
                                            "exam" =>$exam,
                                            "total"=> $total,
                                            "lecturer"=>@\Auth::user()->fund,
                                            "grade" => $grade,
                                            "gpoint" =>round(( $credit*$gradePoint),2),
                                        )

                                    );
                                    $cgpa= number_format(@(( $credit*$gradePoint)/$credit), 2, '.', ',');

                                    $oldCgpa= Models\StudentModel::where("INDEXNO",$indexNo)->select("CGPA","CLASS")->first();
                                    $newCgpa=$cgpa+$oldCgpa->CGPA;
                                    $class=$sys->getClass($newCgpa);
                                    Models\StudentModel::where("INDEXNO",$indexNo)->update(array("CGPA"=>$newCgpa,"CLASS"=>$class));

                                    \DB::commit();
                                }



                            } else {
                                return redirect('upload/legacy')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognized students for $programme .Please upload only  students for  $programme!</span> ");


                            }
                        }


                        return redirect('/dashboard')->with("success",  " <span style='font-weight:bold;font-size:13px;'> $totalRecords Marks  successfully uploaded !</span> ");


                    } else {
                        return redirect('upload/legacy')->with("error", " <span style='font-weight:bold;font-size:13px;'>File is empty</span> ");

                    }
                } else {
                    return redirect('upload/legacy')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload only Excel file!</span> ");

                }






            }

        }
    }
    public function uploadMarks(Request $request, SystemController $sys){

        $this->validate($request, [

            'file' => 'required',
            'course' => 'required',
            'sem' => 'required',
            'year' => 'required',
            'level' => 'required',
        ]);
        // dd($request);
        if(@\Auth::user()->role=='HOD' || @\Auth::user()->department=='top' || @\Auth::user()->department=='Tptop' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop'|| @\Auth::user()->role=='Dean' || @\Auth::user()->role=='Lecturer'){
            $array = $sys->getSemYear();
            $sem = $array[0]->SEMESTER;
            $year = $array[0]->YEAR;
            $resultOpen = $array[0]->ENTER_RESULT;
            if ($resultOpen == 1) {


                $valid_exts = array('csv', 'xls', 'xlsx'); // valid extensions
                $file = $request->file('file');
                $path = $request->file('file')->getRealPath();

                $ext = strtolower($file->getClientOriginalExtension());

                $semester = $request->input('sem');
                $year1 = $request->input('year');
                $course =  $request->input('course') ;
                //$programme = $request->input('program');
                $level = $request->input('level');
                $studentIndexNo = $sys->getStudentIDfromIndexno($request->input('student'));


                if (in_array($ext, $valid_exts)) {

                    $data = Excel::load($path, function($reader) {

                    })->get();
                    //  dd($data);
                    if (!empty($data) && $data->count()) {


                        foreach ($data as $key => $value) {

                            $totalRecords = count($data);



                            //$studentDb= $sys->getStudentIDfromIndexno('0'.$value->index_no);
                            //print_r($value);

                            $studentDb=$value->indexno  ;
                            // dd($studentDb);
                            $courseArr= $sys->getCourseMountedInfo($course);
                            // dd($courseArr);
                            $courseDb= $courseArr[0]->ID;
                            $courseCreditDb= $courseArr[0]->COURSE_CREDIT;
                            $courseLecturerDb= $courseArr[0]->LECTURER;
                            $courseName=$sys->getCourseCodeByIDArray($courseArr[0]->COURSE);
                            $displayCourse=$courseName[0]->COURSE_NAME;
                            $displayCode=$courseName[0]->COURSE_CODE;
                            $studentSearch = $sys->studentSearchByCode($year,$semester,$courseDb,$studentDb); // check if the students in the file tally with registered students
                            //dd($studentDb);
//                        if (@in_array($studentDb, $studentSearch)) {
                            $indexNo=$value->index_no;
                            $quiz1=$value->quiz1;
                            $quiz2=$value->quiz2;
                            $midsem=$value->midsem1;
                            $exam=$value->exam;
                            $total= round(($quiz2+$quiz1+$midsem+$exam),2);
                            $programmeDetail=$sys->getCourseProgrammeMounted($displayCode);

                            $program=$sys->getProgramArray($programmeDetail);
                            //dd($program);
                            $gradeArray = @$sys->getGrade($total, $program[0]->GRADING_SYSTEM);
                            $grade = @$gradeArray[0]->grade;

                            // dd($gradeArray );
                            $gradePoint =round(( @$gradeArray[0]->value * @$courseArr[0]->COURSE_CREDIT),2);
                            //$cgpa= number_format(@(( $gradePoint)/@$courseArr[0]->COURSE_CREDIT), 2, '.', ',');
                            //  $oldCgpa= @Models\StudentModel::where("INDEXNO",$studentDb)->select("CGPA","CLASS")->first();
                            // $newCgpa=@$cgpa+$oldCgpa->CGPA;
                            //$class=@$sys->getClass($newCgpa);
                            //Models\StudentModel::where("INDEXNO",$studentDb)->update(array("CGPA"=>$newCgpa,"CLASS"=>$class));

                            Models\AcademicRecordsModel::where("indexno", $studentDb)->where("code", $course)->where("sem",$semester)->where("year",$year1)->update(array("quiz1" => $quiz1, "quiz2" => $quiz2, "quiz3" =>0, "midSem1" => $midsem, "exam" => $exam, "total" => $total, "lecturer" =>$courseLecturerDb,'grade' => $grade, 'gpoint' => $gradePoint));

                            \DB::commit();



//                       } else {
//                                return redirect('/upload/marks')->with("error", " <span style='font-weight:bold;font-size:13px;'>File contain unrecognized students for $displayCourse - $displayCode.please upload only registered students for  $displayCourse - $displayCode  as downloaded from the system!</span> ");
//                            
//                                  
//                            } 
                        }


                        return redirect('/registered_courses')->with("success",  " <span style='font-weight:bold;font-size:13px;'> $totalRecords Marks  successfully uploaded for  $displayCourse - $displayCode!</span> ");


                    } else {
                        return redirect('/upload/marks')->with("error", " <span style='font-weight:bold;font-size:13px;'>File is empty</span> ");

                    }
                } else {
                    return redirect('/upload/marks')->with("error", " <span style='font-weight:bold;font-size:13px;'>Please upload only Excel file!</span> ");

                }




            }
            else{
                throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');
            }
        }
        else{

            redirect("/dashboard")->with('error','Entering of marks has ended contact the Dean of your School');

        }
    }
    public function batchRegistration(Request $request,SystemController $sys){

        if (@\Auth::user()->department == 'top' || @\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Support' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop') {
            if ($request->isMethod("get")) {

                return view('courses.batchRegister')->with('year', $sys->years())
                    ->with("course",$sys->getMountedCourseList())
                    ->with("level",$sys->getLevelList())
                    ->with('program', $sys->getProgramList());


            }
            else{

            }
        }
        else{
            return redirect("/dashboard");
        }
    }
    public function processBatchRegistration(Request $request,SystemController $sys){
        $this->validate($request, [

            'program' => 'required',
        ]);
        //dd($request);
        $array = $sys->getSemYear();
        $sem = $array[0]->SEMESTER;
        $year = $array[0]->YEAR;
        $status = $array[0]->STATUS;
        if ($status == 1) {

            // $policy=$sys->getRegiistrationProtocol($student);
            $level=$request->input("level");
            $program=$request->input("program");
            \DB::beginTransaction();
            try {
                if(!empty($level)){
                    $query=  Models\StudentModel::where("LEVEL",$level)->where("PROGRAMMECODE",$program)->get();
                    $courses= Models\MountedCourseModel::where("COURSE_LEVEL",$level)->where("PROGRAMME",$program)->where("COURSE_YEAR",$year)->where("COURSE_SEMESTER",$sem)->get();

                    foreach($query as $row){
                        $studentID=$sys->getStudentIDfromIndexno($row->INDEXNO);
                        $indexno=$row->INDEXNO;
                        $totalHours=0;

                        foreach($courses as $data){

                            $type=$data->COURSE_TYPE;

                            $level=$data->COURSE_LEVEL;
                            $credit=$data->COURSE_CREDIT;
                            $lecturer=$data->LECTURER;
                            $code=$data->COURSE_CODE;
                            $course=$data->ID;
                            $totalHours+=$credit;


                            $queryModel=new Models\AcademicRecordsModel();
                            $queryModel->course=$course;
                            $queryModel->code=$code;
                            $queryModel->indexno=$indexno;
                            $queryModel->credits=$credit;
                            $queryModel->student=$studentID;
                            $queryModel->yrgp=$row->GRADUATING_GROUP;
                            $queryModel->year=$year;
                            $queryModel->sem=$sem;
                            $queryModel->level=$level;
                            $queryModel->lecturer=$lecturer;
                            $queryModel->dateRegistered=\date('Y-m-d H:i:s');
                            $queryModel->save();
                            // \DB::commit();
                            $oldHours = Models\StudentModel::where("INDEXNO", $indexno)->first();
                            $durationCredit = $sys->getProgrammeMinCredit(@$oldHours->PROGRAMMECODE);

                            $newHours = @$oldHours->TOTAL_CREDIT_DONE + $totalHours;

                            $leftHours = $durationCredit - $newHours;

                            Models\StudentModel::where('INDEXNO', $indexno)->update(array('TOTAL_CREDIT_DONE' => $newHours, 'CREDIT_LEFT_COMPLETE' => $leftHours, 'REGISTERED' => '1','INDEXNO'=>$indexno,'STATUS'=>'In School'));
                            \DB::commit();
                        }
                    }
                }
                else{
                    $query=  Models\StudentModel::where("PROGRAMMECODE",$program)->where("BILL_OWING","<=","500")->get();
                    $courses= Models\MountedCourseModel::where("PROGRAMME",$program)->where("COURSE_YEAR",$year)->where("COURSE_SEMESTER",$sem)->get();

                    foreach($query as $row){
                        $studentID=$sys->getStudentIDfromIndexno($row->INDEXNO);
                        $indexno=$row->INDEXNO;
                        $totalHours=0;
                        foreach($courses as $data){

                            $type=$data->COURSE_TYPE;

                            $level=$data->COURSE_LEVEL;
                            $credit=$data->COURSE_CREDIT;
                            $lecturer=$data->LECTURER;
                            $code=$data->COURSE_CODE;
                            $course=$data->ID;
                            $totalHours+=$credit;
                            // overwrite registered courses for the sem and the year
                            @Models\AcademicRecordsModel::query()->where('indexno', $indexno)
                                ->where('year', $year)
                                ->where('sem', $sem)
                                ->delete() ;

                            $queryModel=new Models\AcademicRecordsModel();
                            $queryModel->course=$course;
                            $queryModel->code=$code;
                            $queryModel->indexno=$indexno;
                            $queryModel->credits=$credit;
                            $queryModel->student=$studentID;
                            $queryModel->yrgp=$row->GRADUATING_GROUP;
                            $queryModel->year=$year;
                            $queryModel->sem=$sem;
                            $queryModel->level=$level;
                            $queryModel->lecturer=$lecturer;
                            $queryModel->dateRegistered=\date('Y-m-d H:i:s');
                            $queryModel->save();
                            //   \DB::commit();
                            $oldHours = Models\StudentModel::where("INDEXNO", $indexno)->first();
                            $durationCredit = $sys->getProgrammeMinCredit(@$oldHours->PROGRAMMECODE);

                            $newHours = @$oldHours->TOTAL_CREDIT_DONE + $totalHours;

                            $leftHours = $durationCredit - $newHours;

                            Models\StudentModel::where('INDEXNO', $indexno)->update(array('TOTAL_CREDIT_DONE' => $newHours, 'CREDIT_LEFT_COMPLETE' => $leftHours, 'REGISTERED' => '1','INDEXNO'=>$indexno,'STATUS'=>'In School'));
                            \DB::commit();
                        }
                    }
                }
            } catch (\Exception $e) {
                \DB::rollback();
            }
            //return redirect('/courses')->with("success",  " <span style='font-weight:bold;font-size:13px;'>Courses registered successfully</span> ");

        }
        else{
            return redirect("/dashboard")->with("error","Registration has been closed");
        }
    }
    // show form for edit resource
    public function edit(Request $request,$id,SystemController $sys){
        if (@\Auth::user()->department == 'top' || @\Auth::user()->role == 'HOD' || @\Auth::user()->role == 'Support' || @\Auth::user()->role == 'Admin' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop') {
            if ($request->isMethod("get")) {

                $course = Models\CourseModel::where("ID", $id)->firstOrFail();
                $program = $sys->getProgramList2();
                return view('courses.edit')
                    ->with("program", $program)
                    ->with('data', $course);
            } else {
                $this->validate($request, [


                    'program' => 'required',
                    'name' => 'required',
                    'code' => 'required',
                ]);
                $name=$request->input("name");
                $code=$request->input("code");
                $program=$request->input("program");
                // dd($program);
                \DB::beginTransaction();
                try {

                    $query = @Models\CourseModel::where("ID", $id)->update(array("COURSE_NAME" => $name, "COURSE_CODE" => $code, "PROGRAMME" => $program));
                    \DB::commit();
                    if($query){
                        @Models\MountedCourseModel::where("COURSE",$id)->update(array("COURSE_CODE"=>$code,"PROGRAMME"=>$program));
                        \DB::commit();
                        return redirect('/courses')->with("success",  " <span style='font-weight:bold;font-size:13px;'> $name updated successfully</span> ");

                    }

                } catch (\Exception $e) {
                    \DB::rollback();
                }
            }
        } else {
            // throw new HttpException(Response::HTTP_UNAUTHORIZED, 'This action is unauthorized.');

            return redirect("/dashboard");
        }
    }

    public function update(Request $request, $id){

    }
    /**
     * Destroy the given task.
     *
     * @param  Request  $request
     * @param  Task  $task
     * @return Response
     */
    public function destroy(Request $request,   SystemController $sys, Models\CourseModel $course)
    {
        //dd($request->input("id"));
        if(@\Auth::user()->role=='HOD' ||  @\Auth::user()->role=='Support'||  @\Auth::user()->role=='Admin' ||  @\Auth::user()->department=='top' || @\Auth::user()->department=='Tptop' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop'){
            $hod=@\Auth::user()->id;
            $array=$sys->getSemYear();
            $sem=$array[0]->SEMESTER;
            $year=$array[0]->YEAR;


            $query= Models\MountedCourseModel::where('COURSE',$request->input("id"))
                ->where('COURSE_YEAR',$year)
                ->where('COURSE_SEMESTER',$sem)
                ->first();

            if($query==""){

                $query1= Models\CourseModel::where('ID',$request->input("id"))->where("USER",$hod)->delete();

                // \DB::commit();



                return redirect("/courses")->with("success","<span style='font-weight:bold;font-size:13px;'> Course  successfully deleted!</span> ");



            }
            else{
                return redirect("/courses")->with("error","<span style='font-weight:bold;font-size:13px;'>Whoops!! you cannot delete a mounted course</span> ");

            }

        }
        else {
            abort(434, "{!!<b>Unauthorize Access detected</b>!!}");
            redirect("/dashboard");
        }

    }
    // delete mounted courses
    public function destroy_mounted(Request $request,   SystemController $sys, Models\CourseModel $course)
    {
        if(@\Auth::user()->role=='HOD' ||  @\Auth::user()->role=='Support'||  @\Auth::user()->role=='Admin' ||  @\Auth::user()->department=='top' || @\Auth::user()->department=='Tptop' || @\Auth::user()->department=='Tpmid' || @\Auth::user()->department=='Tptop'){

            $array=$sys->getSemYear();
            $sem=$array[0]->SEMESTER;
            $year=$array[0]->YEAR;

            \DB::beginTransaction();
            try {


                Models\MountedCourseModel::where('ID',$request->input("id"))->delete();


                \DB::commit();
                return redirect("/mounted_view")->with("success","<span style='font-weight:bold;font-size:13px;'> Course  successfully deleted!</span> ");


            }catch (\Exception $e) {
                \DB::rollback();
            }
        }
        else {
            abort(434, "{!!<b>Unauthorize Access detected</b>!!}");
            redirect("/dashboard");
        }

    }
    public function courseDownloadExcel($type)

    {


        $data = Models\CourseModel::select('COURSE_CODE','COURSE_NAME','COURSE_LEVEL','COURSE_CREDIT','COURSE_SEMESTER','PROGRAMME')->take(5)->get()->toArray();

        return Excel::create('courses_example', function($excel) use ($data) {

            $excel->sheet('mySheet', function($sheet) use ($data)

            {

                $sheet->fromArray($data);

            });

        })->download($type);

    }

    // naptex broadsheet view
    public function naptexBroadsheet(Request $request, SystemController $sys){
        return view('courses.noticeboard')->with('year', $sys->years())
            ->with('level', $sys->getLevelList())
            ->with("program", $sys->getProgramList());



    }
    // naptex broadsheet view
    public function processNaptexBroadsheet(Request $request, SystemController $sys){


    }

    // noticeboard broadsheet

    public function noticeBoardBroadsheet(Request $request, SystemController $sys){



        return view('courses.noticeboard')->with('year', $sys->years())
            ->with('level', $sys->getLevelList())
            ->with("program", $sys->getProgramList());



    }
    public function processBroadsheet(Request $request, SystemController $sys) {


        \Session::put('level', $request->input("level", ""));
        \Session::put('year', $request->input("year", ""));
        \Session::put('program', $request->input("program", ""));
        \Session::put('sem', $request->input("semester", ""));
        $program=$request->input("program", "");

        $level=$request->input("level", "");
        $semester=$request->input("semester", "");
        $year=$request->input("year", "");


        if ($request->has('search') && trim($request->input('search')) != "") {
            // dd($request);
            $headerQuery= Models\AcademicRecordsModel::where("level",$level)->where("sem",$semester)
                ->where("indexno",$request->input('search'))
                ->where("year",$year)->whereHas('student', function($q)use ($program) {
                    $q->whereHas('programme', function($q)use ($program) {
                        $q->whereIn('PROGRAMMECODE',  array($program));
                    });
                })->orderBy("code")
                ->groupBy("code")
                ->get()->toArray();
        }
        else{
            $headerQuery= Models\AcademicRecordsModel::where("level",$level)->where("sem",$semester)
                ->where("year",$year)->whereHas('student', function($q)use ($program) {
                    $q->whereHas('programme', function($q)use ($program) {
                        $q->whereIn('PROGRAMMECODE',  array($program));
                    });
                })->orderBy("code")
                ->groupBy("code")
                ->get()->toArray();
        }


        $courseArray=array();
        foreach($headerQuery as $row){
            //$courseArray=array();$course=$row['courseId'];
            $course=$row['code'];
            if($course!=""||$course==0){

                $courseArray[]=$course;
            }
            else{
                $courseArray[]="N/A";
            }
        }
        if ($request->has('search') && trim($request->input('search')) != "") {
            $studentData= Models\AcademicRecordsModel::where("level",$level)->where("sem",$semester)
                ->where("indexno",$request->input('search'))
                ->where("year",$year)->whereHas('student', function($q)use ($program) {
                    $q->whereHas('programme', function($q)use ($program) {
                        $q->whereIn('PROGRAMMECODE',  array($program));
                    });
                })->orderBy("indexno")
                ->groupBy("indexno")
                ->select("indexno","level")
                ->get();

        }
        else{
            $studentData=Models\AcademicRecordsModel::where("level",$level)->where("sem",$semester)
                ->where("year",$year)->whereHas('student', function($q)use ($program) {
                    $q->whereHas('programme', function($q)use ($program) {
                        $q->whereIn('PROGRAMMECODE',  array($program));
                    });
                })->orderBy("indexno")
                ->groupBy("indexno")
                ->select("indexno","level")
                ->get();
        }


        return view('courses.noticeboard')->with('year', $sys->years())
            ->with('level', $sys->getLevelList())
            ->with("program", $sys->getProgramList())
            ->with("headers", $headerQuery)
            ->with("course",   $courseArray)
            ->with("years", $request->input("year", ""))
            ->with("programs", $request->input("program", ""))
            ->with("levels", $request->input("level", ""))
            ->with("term", $request->input("semester", ""))
            ->with("student", $studentData);


    }

    public function generateIndexNumber(Request $request, SystemController $sys){

    }

}

