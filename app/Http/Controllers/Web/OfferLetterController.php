<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OfferLetter;
use App\Models\Department;
use App\Http\Controllers\Web\BaseController;

class OfferLetterController extends BaseController
{
    /**
     * Display a listing of offer letters
     */
    public function index()
    {
        $offerLetters = OfferLetter::with(['department', 'createdBy', 'approvedBy'])
            ->when(request('search'), function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('candidate_name', 'like', "%{$search}%")
                      ->orWhere('candidate_email', 'like', "%{$search}%")
                      ->orWhere('position', 'like', "%{$search}%");
                });
            })
            ->when(request('department'), function($query, $department) {
                $query->where('department_id', $department);
            })
            ->when(request('status'), function($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);

        $departments = Department::all();

        return $this->safeView('offer-letters.index', compact('offerLetters', 'departments'));
    }

    /**
     * Show the form for creating a new offer letter
     */
    public function create()
    {
        if (!auth()->user()->isHr()) {
            return back()->with('error', 'Only HR can create offer letters.');
        }

        $departments = Department::where('is_active', true)->get();
        return $this->safeView('offer-letters.create', compact('departments'));
    }

    /**
     * Store a newly created offer letter
     */
    public function store(Request $request)
    {
        if (!auth()->user()->isHr()) {
            return back()->with('error', 'Only HR can create offer letters.');
        }

        $request->validate([
            'candidate_name' => 'required|string|max:255',
            'candidate_email' => 'required|email',
            'candidate_phone' => 'nullable|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'offered_salary' => 'required|numeric|min:0',
            'offer_date' => 'required|date',
            'joining_date' => 'required|date|after:offer_date',
            'job_description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->id();
        $data['status'] = 'draft';
        $data['salary_currency'] = 'INR';

        OfferLetter::create($data);

        return redirect()->route('web.offer-letters.index')
            ->with('success', 'Offer letter created successfully!');
    }

    /**
     * Display the specified offer letter
     */
    public function show(OfferLetter $offerLetter)
    {
        $offerLetter->load(['department', 'createdBy', 'approvedBy']);
        return $this->safeView('offer-letters.show', compact('offerLetter'));
    }

    /**
     * Show the form for editing the specified offer letter
     */
    public function edit(OfferLetter $offerLetter)
    {
        if (!auth()->user()->isHr()) {
            return back()->with('error', 'Only HR can edit offer letters.');
        }

        $departments = Department::where('is_active', true)->get();
        return $this->safeView('offer-letters.edit', compact('offerLetter', 'departments'));
    }

    /**
     * Update the specified offer letter
     */
    public function update(Request $request, OfferLetter $offerLetter)
    {
        if (!auth()->user()->isHr()) {
            return back()->with('error', 'Only HR can update offer letters.');
        }

        $request->validate([
            'candidate_name' => 'required|string|max:255',
            'candidate_email' => 'required|email',
            'candidate_phone' => 'nullable|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'offered_salary' => 'required|numeric|min:0',
            'offer_date' => 'required|date',
            'joining_date' => 'required|date|after:offer_date',
            'job_description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
        ]);

        $offerLetter->update($request->all());

        return redirect()->route('web.offer-letters.index')
            ->with('success', 'Offer letter updated successfully!');
    }

    /**
     * Remove the specified offer letter
     */
    public function destroy(OfferLetter $offerLetter)
    {
        if (!auth()->user()->isHr()) {
            return back()->with('error', 'Only HR can delete offer letters.');
        }

        $offerLetter->delete();

        return redirect()->route('web.offer-letters.index')
            ->with('success', 'Offer letter deleted successfully!');
    }

    /**
     * Send offer letter
     */
    public function send(OfferLetter $offerLetter)
    {
        if (!auth()->user()->isHr()) {
            return back()->with('error', 'Only HR can send offer letters.');
        }

        if ($offerLetter->status !== 'draft') {
            return back()->with('error', 'Only draft offer letters can be sent.');
        }

        $offerLetter->update(['status' => 'sent']);

        return back()->with('success', 'Offer letter sent successfully!');
    }

    /**
     * Approve offer letter
     */
    public function approve(OfferLetter $offerLetter)
    {
        if (!auth()->user()->isHr() && !auth()->user()->isManager()) {
            return back()->with('error', 'Only HR and managers can approve offer letters.');
        }

        $offerLetter->update([
            'status' => 'accepted',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Offer letter approved successfully!');
    }

    /**
     * Update offer letter status
     */
    public function updateStatus(Request $request, OfferLetter $offerLetter)
    {
        if (!auth()->user()->isHr()) {
            return back()->with('error', 'Only HR can update offer letter status.');
        }

        $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected',
        ]);

        $offerLetter->update($request->only('status'));

        return back()->with('success', 'Offer letter status updated successfully!');
    }

    /**
     * Show offer letter statistics
     */
    public function statistics()
    {
        $data = [
            'totalOffers' => OfferLetter::count(),
            'draftOffers' => OfferLetter::where('status', 'draft')->count(),
            'sentOffers' => OfferLetter::where('status', 'sent')->count(),
            'acceptedOffers' => OfferLetter::where('status', 'accepted')->count(),
            'rejectedOffers' => OfferLetter::where('status', 'rejected')->count(),
            'offersByDepartment' => Department::withCount('offerLetters')->get(),
            'recentOffers' => OfferLetter::with(['department', 'createdBy'])->latest()->take(10)->get(),
        ];

        return $this->safeView('offer-letters.statistics', $data);
    }

    /**
     * Show offer letters by department
     */
    public function byDepartment(Department $department)
    {
        $offerLetters = $department->offerLetters()->with(['createdBy', 'approvedBy'])->paginate(15);
        return $this->safeView('offer-letters.by-department', compact('offerLetters', 'department'));
    }
}
