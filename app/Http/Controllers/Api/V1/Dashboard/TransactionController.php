<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\TransactionService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use ResponseTrait;

    public function __construct(protected TransactionService $service)
    {
    }

    public function index()
    {
        try {
            $transactions = $this->service->index();
            return $this->showResponse($transactions, 'transactions.index_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'transactions.errors.index_error');
        }
    }

    public function handleTransaction(Request $request, string $id)
    {
        try {
            $transaction = $this->service->handleTransaction($id, $request);
            return $this->showResponse($transaction, 'transactions.handle_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'transactions.errors.handle_error');
        }
    }

    public function createTransaction(Request $request, string $id)
    {
        try {
            $wallet = $this->service->createTransaction($request, $id);
            return $this->showResponse($wallet, 'transactions.create_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'transactions.errors.create_error');
        }
    }
}
