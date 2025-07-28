<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OfferLetter;
use App\Models\Payroll;

class DashboardController extends Controller
{
    /**
     * Show the main dashboard
     */
    public function index()
    {
        $user = auth()->user();

        $data = [
            'user' => $user,
            'totalDepartments' => Department::count(),
            'totalEmployees' => Employee::count(),
            'activeEmployees' => Employee::where('employment_status', 'active')->count(),
            'totalOfferLetters' => OfferLetter::count(),
            'pendingOffers' => OfferLetter::where('status', 'draft')->count(),
            'totalPayrolls' => Payroll::count(),
            'pendingPayrolls' => Payroll::where('payment_status', 'pending')->count(),
        ];

        // Add role-specific data
        if ($user->isHr() || $user->isManager()) {
            $data['departments'] = Department::with('employees')->get();
            $data['recentEmployees'] = Employee::with(['user', 'department'])->latest()->take(5)->get();
            $data['recentOfferLetters'] = OfferLetter::with('department')->latest()->take(5)->get();
            $data['recentPayrolls'] = Payroll::with('employee')->latest()->take(5)->get();
        }

        return view('dashboard.index', $data);
    }

    /**
     * Show dashboard statistics (HR & Manager only)
     */
    public function statistics()
    {
        $this->authorize('viewStatistics');

        $data = [
            'departments' => Department::withCount('employees')->get(),
            'employeesByStatus' => [
                'active' => Employee::where('employment_status', 'active')->count(),
                'inactive' => Employee::where('employment_status', 'inactive')->count(),
                'terminated' => Employee::where('employment_status', 'terminated')->count(),
            ],
            'offerLettersByStatus' => [
                'draft' => OfferLetter::where('status', 'draft')->count(),
                'sent' => OfferLetter::where('status', 'sent')->count(),
                'accepted' => OfferLetter::where('status', 'accepted')->count(),
                'rejected' => OfferLetter::where('status', 'rejected')->count(),
            ],
            'payrollsByStatus' => [
                'pending' => Payroll::where('payment_status', 'pending')->count(),
                'paid' => Payroll::where('payment_status', 'paid')->count(),
                'failed' => Payroll::where('payment_status', 'failed')->count(),
            ],
        ];

        return view('dashboard.statistics', $data);
    }

    /**
     * Show manager approvals (Manager only)
     */
    public function approvals()
    {
        $this->authorize('viewApprovals');

        $data = [
            'pendingOffers' => OfferLetter::where('status', 'draft')->with(['department', 'createdBy'])->get(),
            'pendingPayrolls' => Payroll::where('payment_status', 'pending')->with(['employee'])->get(),
        ];

        return view('dashboard.approvals', $data);
    }
}
