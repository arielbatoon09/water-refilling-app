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
            $totalSalesAmount = 0;
            $totalTransactions = 0;
    
            $refillStatuses = ['Waiting Delivery', 'Pending Payment', 'Delivered', 'Completed', 'Rated'];
            $orderStatuses = ['To Pay', 'To Receive', 'Delivered', 'Completed', 'Rated'];
    
            $statusWiseRefills = [];
            $statusWiseOrders = [];
    
            // Process Refills
            $refills = Refill::whereIn('status', $refillStatuses)->get();
            foreach ($refillStatuses as $status) {
                $filteredRefills = $refills->where('status', $status);
                $statusWiseRefills[$status] = [
                    'total_amount' => $filteredRefills->sum(function ($refill) {
                        return $refill->t_refill_fee + $refill->t_delivery_fee;
                    }),
                    'total_transactions' => $filteredRefills->count(),
                    'row_count' => $filteredRefills->count(),
                ];
            }
    
            // Process Orders
            $orders = Orders::whereIn('status', $orderStatuses)->get();
            foreach ($orderStatuses as $status) {
                $filteredOrders = $orders->where('status', $status);
                
                // Group by `refid` to count only the first row for each `refid`
                $groupedOrders = $filteredOrders->groupBy('refid');
                $rowCount = $groupedOrders->map(function ($group) {
                    return $group->first();
                })->count();
    
                $statusWiseOrders[$status] = [
                    'total_amount' => $filteredOrders->sum('total_item_price'),
                    'total_transactions' => $groupedOrders->count(), // Count distinct `refid`
                    'row_count' => $rowCount, // Count only the first row per `refid`
                ];
            }
    
            // Calculate Totals
            $totalRefillsAmount = $refills->sum(function ($refill) {
                return $refill->t_refill_fee + $refill->t_delivery_fee;
            });
            $totalOrdersAmount = $orders->sum('total_item_price');
            $totalRefillsCount = $refills->count();
            $totalOrdersCount = $orders->groupBy('refid')->count();
    
            $totalSalesAmount = $totalRefillsAmount + $totalOrdersAmount;
            $totalTransactions = $totalRefillsCount + $totalOrdersCount;
    
            return response([
                'status' => 200,
                'message' => 'Tally of success sales fetched successfully.',
                'data' => [
                    'total_sales_amount' => $totalSalesAmount,
                    'total_transactions' => $totalTransactions,
                    'refills' => [
                        'total_amount' => $totalRefillsAmount,
                        'total_transactions' => $totalRefillsCount,
                        'status_wise' => $statusWiseRefills,
                    ],
                    'orders' => [
                        'total_amount' => $totalOrdersAmount,
                        'total_transactions' => $totalOrdersCount,
                        'status_wise' => $statusWiseOrders,
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

    public function getSalesData()
    {
        try {
            // Define the valid statuses for the sales report
            $validStatuses = ['Delivered', 'Completed', 'Rated'];
    
            // Initialize the variables for total sales amounts
            $dailySales = 0;
            $monthlySales = 0;
            $yearlySales = 0;
            $overallSales = 0;
    
            // Calculate sales amounts filtered by status and date
            $dailySales = Orders::whereIn('status', $validStatuses)
                ->whereDate('created_at', Carbon::today())
                ->sum('total_item_price');
    
            $monthlySales = Orders::whereIn('status', $validStatuses)
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_item_price');
    
            $yearlySales = Orders::whereIn('status', $validStatuses)
                ->whereYear('created_at', Carbon::now()->year)
                ->sum('total_item_price');
    
            $overallSales = Orders::whereIn('status', $validStatuses)
                ->sum('total_item_price');
    
            // Return the response with the calculated sales data
            return response([
                'status' => 200,
                'message' => 'Sales data fetched successfully.',
                'data' => [
                    'daily_sales' => $dailySales,
                    'monthly_sales' => $monthlySales,
                    'yearly_sales' => $yearlySales,
                    'total_sales_amount' => $overallSales,
                ],
            ], 200);
        } catch (Throwable $th) {
            // Handle errors and return a response
            return response([
                'status' => 501,
                'source' => 'SalesController',
                'message' => 'Error: ' . $th->getMessage(),
            ], 501);
        }
    }    
}