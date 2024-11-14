<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\GallonType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Refill;
use App\Models\Transaction;
use Throwable;
use DateTime;

class RefillController extends Controller
{
    public function getRefillsByUID()
    {
        return $this->getRefillsByUIDFunction();
    }

    public function addRefilling(Request $request)
    {
        return $this->addRefillingFunction($request);
    }

    public function payRefillOrder(Request $request)
    {
        return $this->payRefillOrderFunction($request);
    }

    public function paidRefillOrder(Request $request)
    {
        return $this->paidRefillOrderFunction($request);
    }

    public function cancelOrder($id)
    {
        return $this->cancelOrderFunction($id);
    }

    public function completeOrder($id)
    {
        return $this->completeOrderFunction($id);
    }

    private function getRefillsByUIDFunction()
    {
        try {
            $userId = auth()->user()->id;
            $getRefillers = Refill::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

            $refillsData = [];

            foreach ($getRefillers as $data) {
                $gallonDetails = json_decode($data->gallon_details, true);
                $filteredGallonDetails = array_map(function ($gallon) {
                    return [
                        'gallon_size' => $gallon['gallon_size'],
                        'no_of_gallon' => $gallon['no_of_gallon']
                    ];
                }, $gallonDetails);

                $refillsData[] = [
                    'id' => $data->id,
                    'user_id' => $data->user_id,
                    "gallon_details" => $filteredGallonDetails,
                    "delivery_type" => $data->delivery_type,
                    "mop" => $data->mop,
                    "delivery_date" => (new DateTime($data->delivery_date))->format('M j, Y'),
                    "t_refill_fee" => $data->t_refill_fee,
                    "t_delivery_fee" => $data->t_delivery_fee,
                    "t_overall_fee" => $data->t_refill_fee + $data->t_delivery_fee,
                    "status" => $data->status,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            }

            return response([
                'status' => 200,
                'source' => 'RefillController',
                'data' => $refillsData,
            ]);
        } catch (Throwable $th) {
            return response($this->response(501, 'Error in ' . $th));
        }
    }

    private function addRefillingFunction(Request $request)
    {
        // Validate request inputs
        $validator = Validator::make($request->all(), [
            'gallon_details' => 'required|array|min:1',
            'delivery_type' => 'required|string',
            'mop' => 'required|string',
            'delivery_date' => 'required|date',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return response([
                'status' => 422,
                'source' => 'RefillController',
                'message' => 'Error Refill Submission!',
                'errors' => $validator->errors()
            ]);
        }
    
        try {
            $gallonDetails = json_encode($request->gallon_details);
            $t_refill_fee = 0;
            $t_delivery_fee = 0;
            $delivery_extra_fee = 0;
            
            foreach ($request->gallon_details as $gallon) { 
                $t_refill_fee += $gallon['gallon_each_price'] * $gallon['no_of_gallon'];
                $t_delivery_fee += $gallon['gallon_each_dfee'] * $gallon['no_of_gallon'];
            }

            $status = 'Pending Payment'; 
            if ($request->delivery_type === 'Door-To-Door' && $request->mop === 'COD') {
                $status = 'Waiting Delivery';
            } elseif ($request->delivery_type === 'Walk-In' && $request->mop === 'COD') {
                $status = 'Visit Shop';
            } elseif ($request->delivery_type === 'Walk-In' && $request->mop === 'Over-The-Counter') {
                $status = 'Completed Order';
            } else {
                $status = 'Pending Payment';
            }
            
            Refill::create([
                "user_id" => auth()->user()->id,
                "gallon_details" => $gallonDetails,
                "delivery_type" => $request->delivery_type,
                "mop" => $request->mop,
                "delivery_date" => $request->delivery_date,
                "t_refill_fee" => $t_refill_fee,
                "t_delivery_fee" => $request->delivery_type === 'Walk-In' ? $t_delivery_fee = 0 : $t_delivery_fee,
                "status" => $status
            ]);
            
            return response([
                'status' => 200,
                'source' => 'RefillController',
                'message' => 'Water refill booking completed successfully!',
            ]);
        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'RefillController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    private function payRefillOrderFunction(Request $request)
    {
        try {
            $gallonDescription = implode(', ', array_map(function($gallon) {
                return $gallon['gallon_size'] . ' (' . $gallon['no_of_gallon'] . 'pcs)';
            }, $request->gallon_details));


            $requestData = [
                'data' => [
                    'attributes' => [
                        'send_email_receipt' => false,
                        'show_description' => true,
                        'show_line_items' => true,
                        'line_items' => [
                            [
                                'currency' => 'PHP',
                                'amount' => intval($request->t_delivery_fee * 100),
                                'description' => 'Payment',
                                'name' => 'Delivery Payment',
                                'quantity' => 1,
                            ],
                            [
                                'currency' => 'PHP',
                                'amount' => intval($request->t_refill_fee * 100),
                                'description' => 'Payment',
                                'name' => 'Refill Payment',
                                'quantity' => 1,
                            ]
                        ],
                        'payment_method_types' => ['gcash', 'paymaya', 'grab_pay'],
                        'reference_number' => 'Ref_'.$request->id,
                        'success_url' => "http://127.0.0.1:5173/success?source=refill&id={$request->id}&mop=" . str_replace(' ', '', $request->mop) . "&deliveryType={$request->deliveryType}",
                        'description' => $gallonDescription,
                    ]
                ]
            ];
    
            $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => "Basic " . env("PAYMONGO_SKEY")
                ])
                ->post('https://api.paymongo.com/v1/checkout_sessions', $requestData);
            
            return $response->json();
    
        } catch (Throwable $th) {
            Log::error('Error in Paymongo checkout session: ', ['error' => $th->getMessage()]);
            return response()->json(['message' => 'An error occurred during the payment process.'], 501);
        }
    }

    private function paidRefillOrderFunction(Request $request)
    {
        try {
            $refill = Refill::where('id', $request->id)->first();

            $status = 'Pending Payment'; 
            if ($request->deliveryType === 'Door-To-Door' && $request->mop === 'OnlinePay') {
                $status = 'Waiting Delivery';
            } elseif ($request->deliveryType === 'Walk-In' && $request->mop === 'OnlinePay') {
                $status = 'Visit Shop';
            } else {
                $status = 'Pending Payment';
            }

            $refill->update([
                'status' => $status,
            ]);

            return response([
                'status' => 200,
                'source' => 'RefillController',
                'message' => 'Updated Refill Status',
            ]);

        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'RefillController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    private function cancelOrderFunction($id)
    {
        try {
            $refill = Refill::where('id', $id)->first();
            $refill->update([
                'status' => 'Cancelled Order'
            ]);

            return response([
                'status' => 200,
                'source' => 'RefillController',
                'message' => 'Cancelled Refill Status',
            ]);

        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'RefillController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    private function completeOrderFunction($id)
    {
        try {
            $refill = Refill::where('id', $id)->first();
            $refill->update([
                'status' => 'Completed Order'
            ]);

            return response([
                'status' => 200,
                'source' => 'RefillController',
                'message' => 'Completed Refill Status',
            ]);

        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'RefillController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

}
