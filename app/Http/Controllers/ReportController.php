<?php

namespace App\Http\Controllers;

use App\Models\Installation;
use App\Models\Component;
use App\Models\Inspection;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceRecord;
use App\Models\CostRecord;
use App\Models\ReplacementForecast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard.
     */
    public function index(Request $request)
    {
        return view('reports.index', $this->getReportData($request));
    }

    /**
     * Display the print-friendly report.
     */
    public function print(Request $request)
    {
        return view('reports.print', $this->getReportData($request));
    }

    /**
     * Prepare all report data.
     */
    private function getReportData(Request $request)
    {
        $installationId = $request->input('installation_id');

        /*
        |--------------------------------------------------------------------------
        | Installations
        |--------------------------------------------------------------------------
        */

        $installations = Installation::orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | Components
        |--------------------------------------------------------------------------
        */

        $componentQuery = Component::query();

        if ($installationId) {
            $componentQuery->where('installation_id', $installationId);
        }

        $components = $componentQuery
            ->with(['installation', 'componentType'])
            ->orderBy('name')
            ->get();

        $componentIds = $components->pluck('id');

        /*
        |--------------------------------------------------------------------------
        | Inspections
        |--------------------------------------------------------------------------
        */

        $inspectionQuery = Inspection::query();

        if ($installationId) {
            $inspectionQuery->where('installation_id', $installationId);
        }

        $totalInspections = $inspectionQuery->count();

        $recentInspections = (clone $inspectionQuery)
            ->with(['installation', 'inspector'])
            ->latest('inspection_date')
            ->take(10)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Component Condition
        |--------------------------------------------------------------------------
        */

        $conditionData = [
            'Excellent' => 0,
            'Good' => 0,
            'Fair' => 0,
            'Poor' => 0,
            'Critical' => 0,
        ];

        if ($componentIds->isNotEmpty()) {

            $latestConditions = DB::table('inspection_items')
                ->join(
                    'inspections',
                    'inspection_items.inspection_id',
                    '=',
                    'inspections.id'
                )
                ->select(
                    'inspection_items.component_id',
                    'inspection_items.condition',
                    'inspections.inspection_date',
                    'inspection_items.id'
                )
                ->whereIn('inspection_items.component_id', $componentIds)
                ->whereNotNull('inspection_items.condition')
                ->orderByDesc('inspections.inspection_date')
                ->orderByDesc('inspection_items.id')
                ->get();

            $processedComponents = [];

            foreach ($latestConditions as $item) {

                if (isset($processedComponents[$item->component_id])) {
                    continue;
                }

                $processedComponents[$item->component_id] = true;

                $condition = strtolower(trim((string) $item->condition));

                switch ($condition) {

                    case 'excellent':
                        $conditionData['Excellent']++;
                        break;

                    case 'good':
                        $conditionData['Good']++;
                        break;

                    case 'fair':
                        $conditionData['Fair']++;
                        break;

                    case 'poor':
                        $conditionData['Poor']++;
                        break;

                    case 'critical':
                        $conditionData['Critical']++;
                        break;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Maintenance Schedules
        |--------------------------------------------------------------------------
        */

        $maintenanceQuery = MaintenanceSchedule::query();

        if ($componentIds->isNotEmpty()) {

            $maintenanceQuery->whereIn(
                'component_id',
                $componentIds
            );

        } elseif ($installationId) {

            $maintenanceQuery->whereRaw('1 = 0');
        }

        $totalMaintenance = $maintenanceQuery->count();

        $scheduledMaintenance = (clone $maintenanceQuery)
            ->where('status', 'Scheduled')
            ->count();

        $dueMaintenance = (clone $maintenanceQuery)
            ->where('status', 'Due Soon')
            ->count();

        $overdueMaintenance = (clone $maintenanceQuery)
            ->where('status', 'Overdue')
            ->count();

        $completedMaintenance = (clone $maintenanceQuery)
            ->where('status', 'Completed')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Maintenance Records
        |--------------------------------------------------------------------------
        */

        $maintenanceRecordsQuery = MaintenanceRecord::query();

        if ($componentIds->isNotEmpty()) {

            $maintenanceRecordsQuery->whereIn(
                'component_id',
                $componentIds
            );

        } elseif ($installationId) {

            $maintenanceRecordsQuery->whereRaw('1 = 0');
        }

        $totalMaintenanceRecords = $maintenanceRecordsQuery->count();

        /*
        |--------------------------------------------------------------------------
        | Cost Analysis
        |--------------------------------------------------------------------------
        */

        $costQuery = CostRecord::query();

        if ($componentIds->isNotEmpty()) {

            $costQuery->whereIn(
                'component_id',
                $componentIds
            );

        } elseif ($installationId) {

            $costQuery->whereRaw('1 = 0');
        }

        $totalCost = $costQuery->sum('amount');

        $maintenanceCost = (clone $costQuery)
            ->where('cost_type', 'Maintenance')
            ->sum('amount');

        $repairCost = (clone $costQuery)
            ->where('cost_type', 'Repair')
            ->sum('amount');

        $replacementCost = (clone $costQuery)
            ->where('cost_type', 'Replacement')
            ->sum('amount');

        $partsCost = (clone $costQuery)
            ->where('cost_type', 'Parts/Materials')
            ->sum('amount');

        $labourCost = (clone $costQuery)
            ->where('cost_type', 'Labour/Service')
            ->sum('amount');

        $otherCost = (clone $costQuery)
            ->where('cost_type', 'Other')
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Replacement Forecast
        |--------------------------------------------------------------------------
        */

        $forecastQuery = ReplacementForecast::query();

        if ($componentIds->isNotEmpty()) {

            $forecastQuery->whereIn(
                'component_id',
                $componentIds
            );

        } elseif ($installationId) {

            $forecastQuery->whereRaw('1 = 0');
        }

        $riskData = [
            'Low' => (clone $forecastQuery)
                ->where('risk_level', 'Low')
                ->count(),

            'Medium' => (clone $forecastQuery)
                ->where('risk_level', 'Medium')
                ->count(),

            'High' => (clone $forecastQuery)
                ->where('risk_level', 'High')
                ->count(),
        ];

        $replacementForecasts = $forecastQuery
            ->with(['component'])
            ->latest('forecast_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statistics = [

            'total_installations' => $installationId
                ? Installation::where('id', $installationId)->count()
                : Installation::count(),

            'total_components' => $components->count(),

            'total_inspections' => $totalInspections,

            'total_maintenance' => $totalMaintenance,

            'total_maintenance_records' => $totalMaintenanceRecords,

            'total_cost' => $totalCost,

            'high_risk' => $riskData['High'],

            'critical_components' => $conditionData['Critical'],

        ];

        return compact(

            'installations',

            'installationId',

            'components',

            'totalInspections',

            'recentInspections',

            'conditionData',

            'totalMaintenance',

            'scheduledMaintenance',

            'dueMaintenance',

            'overdueMaintenance',

            'completedMaintenance',

            'totalMaintenanceRecords',

            'totalCost',

            'maintenanceCost',

            'repairCost',

            'replacementCost',

            'partsCost',

            'labourCost',

            'otherCost',

            'riskData',

            'replacementForecasts',

            'statistics'

        );
    }
}