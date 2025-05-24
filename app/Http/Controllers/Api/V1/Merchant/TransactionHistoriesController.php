<?php

namespace App\Http\Controllers\Api\V1\Merchant;

use App\Filters\TransactionsFilters;
use App\Http\Controllers\Controller;
use App\Models\TransactionHistory;
use App\Traits\ResponseTrait;
use Auth;
use Illuminate\Http\Request;

class TransactionHistoriesController extends Controller
{
    use ResponseTrait;

    public function __construct(
        protected TransactionsFilters $transactionsFilters,
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $wallet = $user->wallet;
        $data = $this->transactionsFilters->applyFilters(TransactionHistory::query())
            ->where('wallet_id', $wallet->id)
            ->get()->append('image_full_path');

        return $this->showResponse($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
