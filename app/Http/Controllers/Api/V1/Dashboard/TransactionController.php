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
            return $this->showResponse($transactions, 'تم جلب كل المعاملات بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب كل المعاملات');
        }
    }

    public function handleTransaction(Request $request, string $id)
    {
        try {
            $transaction = $this->service->handleTransaction($id, $request);
            return $this->showResponse($transaction, 'تم معالجة المعاملة بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما معالجة المعاملة');
        }
    }


    public function createTransaction(Request $request, string $id)
    {
        try {
            $wallet = $this->service->createTransaction($request ,$id);
            return $this->showResponse($wallet,'تمت المعاملة بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء إنجاز المعاملة');
        }
    }
}
