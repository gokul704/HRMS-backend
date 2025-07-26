<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OfferLetter;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfferLetterController extends Controller
{
    /**
     * Display a listing of offer letters
     */
    public function index(Request $request)
    {
        $query = OfferLetter::with(['department', 'createdBy', 'approvedBy']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
    }

        // Filter by department
        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->where('offer_date', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->where('offer_date', '<=', $request->date_to);
        }

        // Search by candidate name or offer ID
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('candidate_name', 'like', "%{$search}%")
                  ->orWhere('offer_id', 'like', "%{$search}%")
                  ->orWhere('candidate_email', 'like', "%{$search}%");
            });
        }

        $offerLetters = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $offerLetters,
        ]);
    }

    /**
     * Store a newly created offer letter
     */
    public function store(Request $request)
    {
        $request->validate([
            'candidate_name' => 'required|string|max:255',
            'candidate_email' => 'required|email',
            'candidate_phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'offered_salary' => 'required|numeric|min:0',
            'salary_currency' => 'required|string|max:3',
            'offer_date' => 'required|date',
            'joining_date' => 'required|date|after:offer_date',
            'job_description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
        ]);

        // Generate offer ID
        $offerId = 'OFF' . str_pad(OfferLetter::count() + 1, 3, '0', STR_PAD_LEFT);

        $offerLetter = OfferLetter::create([
            'offer_id' => $offerId,
            'candidate_name' => $request->candidate_name,
            'candidate_email' => $request->candidate_email,
            'candidate_phone' => $request->candidate_phone,
            'position' => $request->position,
            'department_id' => $request->department_id,
            'offered_salary' => $request->offered_salary,
            'salary_currency' => $request->salary_currency,
            'offer_date' => $request->offer_date,
            'joining_date' => $request->joining_date,
            'job_description' => $request->job_description,
            'benefits' => $request->benefits,
            'terms_and_conditions' => $request->terms_and_conditions,
            'status' => 'draft',
            'created_by' => $request->user()->id,
        ]);

        $offerLetter->load(['department', 'createdBy']);

        return response()->json([
            'success' => true,
            'message' => 'Offer letter created successfully',
            'data' => $offerLetter,
        ], 201);
    }

    /**
     * Display the specified offer letter
     */
    public function show(OfferLetter $offerLetter)
    {
        $offerLetter->load(['department', 'createdBy', 'approvedBy']);

        return response()->json([
            'success' => true,
            'data' => $offerLetter,
        ]);
    }

    /**
     * Update the specified offer letter
     */
    public function update(Request $request, OfferLetter $offerLetter)
    {
        $request->validate([
            'candidate_name' => 'sometimes|string|max:255',
            'candidate_email' => 'sometimes|email',
            'candidate_phone' => 'nullable|string|max:20',
            'position' => 'sometimes|string|max:255',
            'department_id' => 'sometimes|exists:departments,id',
            'offered_salary' => 'sometimes|numeric|min:0',
            'salary_currency' => 'sometimes|string|max:3',
            'offer_date' => 'sometimes|date',
            'joining_date' => 'sometimes|date',
            'job_description' => 'nullable|string',
            'benefits' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
        ]);

        // Only allow updates if offer is in draft status
        if (!$offerLetter->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update offer letter that is not in draft status',
            ], 400);
        }

        $offerLetter->update($request->all());
        $offerLetter->load(['department', 'createdBy']);

        return response()->json([
            'success' => true,
            'message' => 'Offer letter updated successfully',
            'data' => $offerLetter,
        ]);
    }

    /**
     * Remove the specified offer letter
     */
    public function destroy(OfferLetter $offerLetter)
    {
        // Only allow deletion if offer is in draft status
        if (!$offerLetter->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete offer letter that is not in draft status',
            ], 400);
        }

        $offerLetter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Offer letter deleted successfully',
        ]);
    }

    /**
     * Send offer letter to candidate
     */
    public function send(OfferLetter $offerLetter)
    {
        if (!$offerLetter->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft offers can be sent',
            ], 400);
        }

        $offerLetter->update(['status' => 'sent']);

        // Here you would typically send an email to the candidate
        // For now, we'll just update the status

        return response()->json([
            'success' => true,
            'message' => 'Offer letter sent successfully',
            'data' => $offerLetter,
        ]);
    }

    /**
     * Approve offer letter
     */
    public function approve(Request $request, OfferLetter $offerLetter)
    {
        $request->validate([
            'approved_by' => 'required|exists:users,id',
        ]);

        if (!$offerLetter->isDraft()) {
            return response()->json([
                'success' => false,
                'message' => 'Only draft offers can be approved',
            ], 400);
        }

        $offerLetter->approve($request->approved_by);

        return response()->json([
            'success' => true,
            'message' => 'Offer letter approved successfully',
            'data' => $offerLetter,
        ]);
    }

    /**
     * Update offer letter status
     */
    public function updateStatus(Request $request, OfferLetter $offerLetter)
    {
        $request->validate([
            'status' => 'required|in:draft,sent,accepted,rejected,expired',
            'response_notes' => 'nullable|string',
        ]);

        $data = ['status' => $request->status];

        if ($request->status === 'accepted' || $request->status === 'rejected') {
            $data['response_date'] = now();
            $data['response_notes'] = $request->response_notes;
        }

        $offerLetter->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Offer letter status updated successfully',
            'data' => $offerLetter,
        ]);
    }

    /**
     * Get offer letter statistics
     */
    public function statistics()
    {
        $stats = [
            'total_offers' => OfferLetter::count(),
            'draft_offers' => OfferLetter::where('status', 'draft')->count(),
            'sent_offers' => OfferLetter::where('status', 'sent')->count(),
            'accepted_offers' => OfferLetter::where('status', 'accepted')->count(),
            'rejected_offers' => OfferLetter::where('status', 'rejected')->count(),
            'expired_offers' => OfferLetter::where('status', 'expired')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get offer letters by department
     */
    public function byDepartment(Department $department)
    {
        $offerLetters = $department->offerLetters()
            ->with(['createdBy', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'department' => $department,
                'offer_letters' => $offerLetters,
            ],
        ]);
    }
}
