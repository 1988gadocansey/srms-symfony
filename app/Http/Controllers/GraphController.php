<?php
/**
 * Created by PhpStorm.
 * User: gadoo
 * Date: 19/06/2018
 * Time: 6:32 PM
 */

namespace App\Http\Controllers;
use App\Charts\StudentChart;
use App\User;
class GraphController extends Controller
{

    public function student(){

        $student=new StudentChart();
        $student->dataset('Sample', 'bar', [100, 65, 84, 45, 90])

            ->options(['borderColor' => '#ff0000']);
        return view('graphs.student', ['chart' => $student]);

    }
    public function chartjs()
    {
        $viewer = User::select(\DB::raw("SUM(id) as count"))
            ->orderBy("created_at")
            ->groupBy(\DB::raw("year(created_at)"))
            ->get()->toArray();
        $viewer = array_column($viewer, 'count');

        $click = User::select(\DB::raw("SUM(id) as count"))
            ->orderBy("created_at")
            ->groupBy(\DB::raw("year(created_at)"))
            ->get()->toArray();
        $click = array_column($click, 'count');


        return view('graphs.try')
            ->with('viewer',json_encode($viewer,JSON_NUMERIC_CHECK))
            ->with('click',json_encode($click,JSON_NUMERIC_CHECK));
    }
}