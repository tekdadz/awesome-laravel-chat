<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Gate;
use App\Helper\Helper;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use Artisanry\ChartJS\Facades\ChartJS;
use Artisanry\ChartJS\ChartJSServiceProvider;
use Artisanry\ChartJS\Builder;
class AsteroidsController extends Controller
{

    public function index(Request $request)
    {
        $date = explode(" - ", request()->input('from-to', ""));
        if(count($date) != 2)
        {
            $date = [now()->subDays(7)->format("Y-m-d"), now()->format("Y-m-d")];
        }
        $helper = new Helper();
        $table=array();
        $data = array();
        $label=array();
        $chartdata = array();
        if(!empty($date)){
            $data = $helper->APINASA($date[0],$date[1]);
            $table = $helper->TableNASA($date[0],$date[1]);
            foreach ($data as $key => $value){
                $label[] = $value['date'];
                $chartdata[] = $value['count'];
            }
        }
        if ($request->ajax()) {
            $table = Datatables::of($table);
            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');
            $table->rawColumns(['actions', 'placeholder']);
            return $table->make(true);
        }
        $areaChart = ChartJS::name('asteroids')
            ->type('Bar')
            ->element('asteroids')
            ->dimension(250)
            ->labels($label)
            ->datasets([[
                'label' => 'Asteroids',
                'fillColor' => 'rgba(210, 214, 222, 1)',
                'strokeColor' => 'rgba(210, 214, 222, 1)',
                'pointColor' => 'rgba(210, 214, 222, 1)',
                'pointStrokeColor' => '#c1c7d1',
                'pointHighlightFill' => '#fff',
                'pointHighlightStroke' => 'rgba(220,220,220,1)',
                'data' => $chartdata,
            ]])->options([
                'showScale' => true,
                'scaleShowGridLines' => false,
                'scaleGridLineColor' => 'rgba(0,0,0,.05)',
                'scaleGridLineWidth' => 1,
                'scaleShowHorizontalLines' => true,
                'scaleShowVerticalLines' => true,
                'bezierCurve' => true,
                'bezierCurveTension' => 0.3,
                'pointDot' => false,
                'pointDotRadius' => 4,
                'pointDotStrokeWidth' => 1,
                'pointHitDetectionRadius' => 20,
                'datasetStroke' => true,
                'datasetStrokeWidth' => 2,
                'datasetFill' => true,
                'legendTemplate' => '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
                'maintainAspectRatio' => false,
                'responsive' => true,
            ]);
        return view('admin.asteroids.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(Request $request)
    {
        //
    }

    public function update(Request $request)
    {
        //
    }

    public function show(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        //
    }
}
