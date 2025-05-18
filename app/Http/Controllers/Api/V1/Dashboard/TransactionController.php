<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\TransactionService;
use App\Traits\ResponseTrait;

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
            return $this->showResponse($transactions, 'تم جلب طل المعاملات بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب كل المعاملات');
        }
    }
}
