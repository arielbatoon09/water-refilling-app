<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Refill;
use App\Models\Addresses;
use Throwable;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function getAllSuccessSales()
    {
        try {
            // Fetch Refills data with success status
            $getRefillers = Refill::whereIn('status', ['Delivered', 'Completed', 'Rated'])
                ->orderBy('created_at', 'desc')
                ->with('User')
                ->get();

            $refillsData = [];

            foreach ($getRefillers as $data) {
                $gallonDetails = json_decode($data->gallon_details, true);

                // Combine gallon details into a single string
                $filteredGallonDetails = implode(', ', array_map(function ($gallon) {
                    return "{$gallon['gallon_size']} - {$gallon['no_of_gallon']} Gallon(s)";
                }, $gallonDetails));

                $refillsData[] = [
                    'id' => "Refill ID: #" . $data->id,
                    'uid' => $data->user->id,
                    'name' => $data->user->name,
                    'details' => $filteredGallonDetails,
                    'mop' => $data->mop,
                    'amount' => $data->t_refill_fee + $data->t_delivery_fee,
                    'created_at' => $data->created_at,
                    'type' => 'Refill',
                    'updated_at' => Carbon::parse($data->updated_at)->format('M. d, Y \a\t h:i A'),
                ];
            }

            // Fetch Orders data with success status
            $orders = Orders::whereIn('status', ['Delivered', 'Completed', 'Rated'])
                ->with(['user', 'cart.product'])
                ->orderBy('created_at', 'desc')
                ->get();

            $groupedOrders = $orders->groupBy('refid');
            $ordersData = [];

            foreach ($groupedOrders as $refid => $orderItems) {
                $firstItem = $orderItems->first();
                $mop = $firstItem->mop;
                $status = $firstItem->status;
                $deliveryType = $firstItem->delivery_type;
                $user = $firstItem->user;

                // Combine order details into a single string
                $orderDetailsString = implode(', ', array_map(function ($item) {
                    return "{$item->cart->product->item_name} - {$item->order_quantity} Item(s)";
                }, $orderItems->all()));

                $ordersData[] = [
                    'id' => $refid,
                    'uid' => $user->id,
                    'name' => $user->name,
                    'details' => $orderDetailsString,
                    'mop' => $mop,
                    'amount' => $orderItems->sum('total_item_price'),
                    'created_at' => $firstItem->created_at,
                    'type' => 'Order',
                    'updated_at' => Carbon::parse($firstItem->updated_at)->format('M. d, Y \a\t h:i A'),
                ];
            }

            // Combine Refills and Orders data
            $salesData = array_merge($refillsData, $ordersData);

            // Sort by created_at descending
            usort($salesData, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });

            // Return combined sales data
            return response([
                'status' => 200,
                'message' => 'Sales data fetched successfully.',
                'data' => $salesData,
            ], 200);
        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'SalesController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }

    public function tallySuccessSales()
    {
        try {
            // Initialize counters for total sales and transactions
            $totalSalesAmount = 0;
            $totalTransactions = 0;

            // Fetch successful Refills
            $refills = Refill::whereIn('status', ['Delivered', 'Completed', 'Rated'])->get();
            $totalRefillsAmount = $refills->sum(function ($refill) {
                return $refill->t_refill_fee + $refill->t_delivery_fee;
            });
            $totalRefillsCount = $refills->count();

            // Fetch successful Orders
            $orders = Orders::whereIn('status', ['Delivered', 'Completed', 'Rated'])->get();
            $totalOrdersAmount = $orders->sum('total_item_price');
            $totalOrdersCount = $orders->groupBy('refid')->count(); // Count unique transactions by refid

            // Calculate totals
            $totalSalesAmount = $totalRefillsAmount + $totalOrdersAmount;
            $totalTransactions = $totalRefillsCount + $totalOrdersCount;

            // Return the tally
            return response([
                'status' => 200,
                'message' => 'Tally of success sales fetched successfully.',
                'data' => [
                    'total_sales_amount' => $totalSalesAmount,
                    'total_transactions' => $totalTransactions,
                    'refills' => [
                        'total_amount' => $totalRefillsAmount,
                        'total_transactions' => $totalRefillsCount,
                    ],
                    'orders' => [
                        'total_amount' => $totalOrdersAmount,
                        'total_transactions' => $totalOrdersCount,
                    ],
                ],
            ], 200);
        } catch (Throwable $th) {
            return response([
                'status' => 501,
                'source' => 'SalesController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }
}