<?php

namespace App\Services\Dashboard;

use App\Models\VisaArrangement;
use Illuminate\Http\Request;

/**
 * Class VisaArrangementService.
 */
class VisaArrangementService
{

    /**
     * List all visa arrangements.
     * @return \Illuminate\Database\Eloquent\Collection<int, \Illuminate\Database\Eloquent\Builder<VisaArrangement>>
     */
    public function list()
    {
        $arrangements = VisaArrangement::with(['user', 'visa'])
            ->orderBy('created_at', 'desc')
            ->get();
        return $arrangements;
    }


    public function show(string $id)
    {
        $arrangement = VisaArrangement::with(['user', 'visa', 'fields'])
            ->findOrFail($id);
        return $arrangement;
    }

    public function accept(string $id, $locale = 'en')
    {
        $arrangement = VisaArrangement::findOrFail($id);
        $cover_letter = $arrangement->visa->coverLetters()
            ->where('locale', $locale)
            ->first();
        $arrangement->update([
            'status' => 'accepted',
            'cover_letter' => $cover_letter->content
        ]);
        return $arrangement;
    }

    public function reject(string $id)
    {
        $arrangement = VisaArrangement::findOrFail($id);
        $arrangement->update([
            'status' => 'rejected',
            'cover_letter' => null
        ]);
        return $arrangement;
    }


    public function attacheFile(string $id, Request $request)
    {
        $request->validate(['file' => 'required|mim:pdf']);

        $path = $request->file('file')->storePublicly('visa_arrangement');
        $arrangement = VisaArrangement::findOrFail($id);
        $arrangement->update([
            'pdf_path' => $path
        ]);
    }
}
