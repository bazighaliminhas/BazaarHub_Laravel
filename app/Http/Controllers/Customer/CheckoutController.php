<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\KuickPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected KuickPayService $kuickPay;

    public function __construct(KuickPayService $kuickPay)
    {
        $this->kuickPay = $kuickPay;
    }

    private function getCartTotals($cartItems)
    {
        $subtotal = $cartItems->sum(
            fn($item) => ($item->product->sale_price ?? $item->product->price) * $item->quantity
        );
        $shipping = 150;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total'    => $subtotal + $shipping,
        ];
    }

    public function index()
    {
        $user      = auth('web')->user();
        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')->with('error', 'Your cart is empty!');
        }

        $totals = $this->getCartTotals($cartItems);

        return view('customer.pages.checkout', [
            'cartItems' => $cartItems,
            'subtotal'  => $totals['subtotal'],
            'shipping'  => $totals['shipping'],
            'total'     => $totals['total']
        ]);
    }

    public function inquiry(Request $request)
    {
        $request->validate(['consumer_number' => 'required|string']);

        try {
            $result = $this->kuickPay->billInquiry($request->consumer_number);

            if ($result['success'] && isset($result['data']['response_Code'])) {
                $code = $result['data']['response_Code'];
                if ($code === '00') {
                    return response()->json(['success' => true, 'data' => $result['data'], 'message' => 'Bill found!']);
                }
            }

            if ($request->consumer_number === '0000812345') {
                return response()->json([
                    'success' => true,
                    'demo'    => true,
                    'data'    => [
                        'response_Code'         => '00',
                        'consumer_Detail'       => auth('web')->user()->name,
                        'due_date'              => now()->addDays(7)->format('Ymd'),
                        'amount_within_dueDate' => '+' . str_pad((int)(session('checkout_total', 1000) * 100), 15, '0', STR_PAD_LEFT),
                        'bill_status'           => 'U',
                    ],
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Bill not found.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Service error.'], 500);
        }
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'full_name'       => 'required|string|max:100',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string|max:255',
            'city'            => 'required|string|max:100',
            'consumer_number' => 'required|string',
        ]);

        $user = auth('web')->user();
        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty!');
        }

        $totals = $this->getCartTotals($cartItems);
        $tranAuthId = strtoupper(Str::random(8));

        // 1. Payment Simulation Logic
        $isDemo = ($request->consumer_number === '0000812345');
        if (!$isDemo) {
            $paymentResult = $this->kuickPay->billPayment($request->consumer_number, $tranAuthId, $totals['total'], now()->format('Ymd'), now()->format('His'));
            if (!($paymentResult['success'] && ($paymentResult['data']['response_Code'] ?? null) === '00')) {
                return back()->withInput()->with('error', 'Payment failed.');
            }
        } else {
            $tranAuthId = 'DEMO-' . $tranAuthId;
        }

        // 2. Database Action
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id'         => $user->id,
                'order_number'    => 'BH-' . strtoupper(Str::random(8)),
                'full_name'       => $request->full_name,
                'phone'           => $request->phone,
                'address'         => $request->address,
                'city'            => $request->city,
                'consumer_number' => $request->consumer_number,
                'tran_auth_id'    => $tranAuthId,
                'payment_method'  => 'kuickpay',
                'payment_status'  => 'paid',
                'subtotal'        => $totals['subtotal'],
                'shipping'        => $totals['shipping'],
                'total'           => $totals['total'],
                'status'          => 'processing',
                // 'notes'           => $request->notes,
            ]);

            foreach ($cartItems as $item) {
                // Check if vendor_id exists to satisfy the database constraint
                if (!$item->product || !$item->product->vendor_id) {
                    throw new \Exception("The product '{$item->product->name}' is missing a Vendor ID.");
                }

                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'vendor_id'    => $item->product->vendor_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->product->sale_price ?? $item->product->price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => ($item->product->sale_price ?? $item->product->price) * $item->quantity,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            $user->cart()->delete();
            DB::commit();

            return redirect()->route('customer.order.success', $order->order_number)->with('success', 'Order placed!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Error: ' . $e->getMessage());
            
            // THIS WILL SHOW YOU THE REAL ERROR ON THE SCREEN
            return back()->withInput()->with('error', 'DEBUG ERROR: ' . $e->getMessage());
        }
    }

    public function success(string $orderNumber)
    {
        $order = Order::with('items.product')->where('order_number', $orderNumber)->where('user_id', auth('web')->id())->firstOrFail();
        return view('customer.pages.order-success', compact('order'));
    }
}