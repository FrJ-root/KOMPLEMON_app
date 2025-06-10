<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Get sales evolution data for chart
     */
    public function getSalesEvolution(Request $request)
    {
        $period = $request->input('period', '30');
        $type = $request->input('type', 'daily');
        
        $startDate = Carbon::now()->subDays(intval($period));
        $endDate = Carbon::now();
        
        switch($type) {
            case 'monthly':
                $format = 'Y-m';
                $labelFormat = 'M Y';
                $groupBy = "DATE_FORMAT(created_at, '%Y-%m')";
                break;
            case 'weekly':
                $format = 'Y-W';
                $labelFormat = '\Week W, Y';
                $groupBy = "CONCAT(YEAR(created_at), '-', WEEK(created_at))";
                break;
            case 'daily':
            default:
                $format = 'Y-m-d';
                $labelFormat = 'd M';
                $groupBy = "DATE(created_at)";
                break;
        }
        
        // Get sales data from database
        $salesData = DB::table('commandes')
            ->select(DB::raw("{$groupBy} as date"), DB::raw('SUM(total) as total_sales'))
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy(DB::raw($groupBy))
            ->orderBy(DB::raw($groupBy))
            ->get();
        
        // Prepare data structure
        $salesByDate = [];
        foreach ($salesData as $data) {
            $salesByDate[$data->date] = $data->total_sales;
        }
        
        // Generate all dates in the period
        $labels = [];
        $values = [];
        
        if ($type === 'daily') {
            $dates = CarbonPeriod::create($startDate, $endDate);
            foreach ($dates as $date) {
                $formattedDate = $date->format($format);
                $labels[] = $date->format($labelFormat);
                $values[] = $salesByDate[$formattedDate] ?? 0;
            }
        } elseif ($type === 'weekly') {
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $weekYear = $currentDate->format('Y');
                $week = $currentDate->format('W');
                $key = "{$weekYear}-{$week}";
                
                $labels[] = "Week {$week}, {$weekYear}";
                $values[] = $salesByDate[$key] ?? 0;
                
                $currentDate->addWeek();
            }
        } elseif ($type === 'monthly') {
            $currentDate = clone $startDate;
            while ($currentDate <= $endDate) {
                $key = $currentDate->format($format);
                $labels[] = $currentDate->format($labelFormat);
                $values[] = $salesByDate[$key] ?? 0;
                
                $currentDate->addMonth();
            }
        }
        
        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
    
    /**
     * Render sales evolution chart page
     */
    public function showSalesEvolution()
    {
        return view('admin.sales.evolution');
    }
}
