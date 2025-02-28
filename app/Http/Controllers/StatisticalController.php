<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Drug;
use App\Models\Employee;
use App\Models\Medication;
use App\Models\PrescriptionDetail;
use Carbon\Carbon;

class StatisticalController extends Controller
{
    public function index(Request $request)
    {
        // Dữ liệu cho thống kê tài chính
        $monthlyRevenue = $this->getMonthlyRevenue();
        $averageTreatmentCost = $this->getAverageTreatmentCost();
        $growthRate = $this->getGrowthRate();

        // Dữ liệu cho thống kê khám chữa bệnh
        $totalPatients = Patient::count();
        $averageAge = $this->getAverageAge();
        $genderStats = $this->getGenderStats();
        $doctorStats = $this->getDoctorPrescriptionStats();

        // Dữ liệu cho thống kê thuốc
        $mostPrescribedDrugs = $this->getMostPrescribedDrugs();
        $drugStock = $this->getDrugStock();

        // Lấy thông tin nhân viên
        $employees = Employee::all(); // Lấy tất cả nhân viên từ bảng employee

        return view('statistics.index', compact(
            'monthlyRevenue',
            'averageTreatmentCost',
            'growthRate',
            'totalPatients',
            'averageAge',
            'genderStats',
            'doctorStats',
            'mostPrescribedDrugs',
            'drugStock',
            'employees' // Truyền dữ liệu nhân viên vào view
        ));
    }

    private function getMonthlyRevenue()
    {
        return Invoice::selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();
    }

    private function getAverageTreatmentCost()
    {
        return Invoice::avg('total_amount');
    }

    private function getGrowthRate()
    {
        $currentMonthTotal = Invoice::whereMonth('date', Carbon::now()->month)
            ->sum('total_amount');

        $previousMonthTotal = Invoice::whereMonth('date', Carbon::now()->subMonth()->month)
            ->sum('total_amount');

        return $previousMonthTotal ? (($currentMonthTotal - $previousMonthTotal) / $previousMonthTotal) * 100 : 0;
    }

    private function getAverageAge()
    {
        return Patient::whereNotNull('date_of_birth')
            ->selectRaw('AVG(TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) as average_age')
            ->value('average_age');
    }

    private function getGenderStats()
    {
        return Patient::select('sex')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('sex')
            ->get();
    }

    private function getDoctorPrescriptionStats()
    {
        return Prescription::with('patient')
            ->select('employee_id', \Illuminate\Support\Facades\DB::raw('COUNT(DISTINCT patient_id) as patient_count'))
            ->groupBy('employee_id')
            ->get();
    }

    private function getMostPrescribedDrugs()
    {
        return PrescriptionDetail::select('medication_id', \Illuminate\Support\Facades\DB::raw('COUNT(*) as count'))
            ->groupBy('medication_id')
            ->orderBy('count', 'desc')
            ->take(5)
            ->get();
    }

    private function getDrugStock()
    {
        return Medication::select('medicine_name', 'stock_quantity')->get();
    }
}