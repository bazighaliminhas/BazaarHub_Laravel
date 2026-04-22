<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\KuickPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected KuickPayService $kuickPay;

    public function __construct(KuickPayService $kuickPay)
    {
        $this->kuickPay = $kuickPay;
    }

    // ── Show Checkout Page ────────────────────────────
    public function index()
    {
        $user      = auth('web')->user();
        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('customer.cart')
                ->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(fn($item) =>
            ($item->product->sale_price ?? $item->product->price) * $item->quantity
        );
        $shipping = 150;
        $total    = $subtotal + $shipping;

        return view('customer.pages.checkout', compact(
            'cartItems', 'subtotal', 'shipping', 'total'
        ));
    }

    // ── KuickPay Bill Inquiry (AJAX) ──────────────────
    public function inquiry(Request $request)
    {
        $request->validate([
            'consumer_number' => 'required|string',
        ]);

        $result = $this->kuickPay->billInquiry($request->consumer_number);

        if ($result['success'] && isset($result['data']['response_Code'])) {
            $code = $result['data']['response_Code'];

            if ($code === '00') {
                return response()->json([
                    'success' => true,
                    'data'    => $result['data'],
                    'message' => 'Bill found successfully!',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $this->kuickPay->getResponseMessage($code, 'inquiry'),
            ]);
        }

        // Demo mode — simulate success for testing
        return response()->json([
            'success' => true,
            'demo'    => true,
            'data'    => [
                'response_Code'       => '00',
                'consumer_Detail'     => auth('web')->user()->name,
                'due_date'            => now()->addDays(7)->format('Ymd'),
                'amount_within_dueDate' => '+' . str_pad(
                    (int)(session('checkout_total', 1000) * 100), 15, '0', STR_PAD_LEFT
                ),
                'bill_status'         => 'U',
            ],
            'message' => 'Demo mode: Bill inquiry simulated.',
        ]);
    }

    // ── Process Payment & Place Order ─────────────────
    public function processPayment(Request $request)
    {
        $request->validate([
            'full_name'       => 'required|string|max:100',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string|max:255',
            'city'            => 'required|string|max:100',
            'consumer_number' => 'required|string',
        ]);

        $user      = auth('web')->user();
        $cartItems = $user->cart()->with('product')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty!');
        }

        $subtotal = $cartItems->sum(fn($item) =>
            ($item->product->sale_price ?? $item->product->price) * $item->quantity
        );
        $shipping = 150;
        $total    = $subtotal + $shipping;

        // ── Call KuickPay Payment API ──
        $tranAuthId = strtoupper(Str::random(8));
        $tranDate   = now()->format('Ymd');
        $tranTime   = now()->format('His');

        $paymentResult = $this->kuickPay->billPayment(
            $request->consumer_number,
            $tranAuthId,
            $total,
            $tranDate,
            $tranTime
        );

        // Determine payment status
        $paymentStatus = 'pending';
        $responseCode  = $paymentResult['data']['response_Code'] ?? null;

        if ($paymentResult['success'] && $responseCode === '00') {
            $paymentStatus = 'paid';
        } elseif (isset($paymentResult['demo'])) {
            // Demo/test mode — treat as paid
            $paymentStatus = 'paid';
            $tranAuthId    = 'DEMO-' . $tranAuthId;
        } else {
            return back()
                ->withInput()
                ->with('error', '❌ Payment failed: ' .
                    $this->kuickPay->getResponseMessage($responseCode ?? '05', 'payment'));
        }

        // ── Create Order ──
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
            'payment_status'  => $paymentStatus,
            'subtotal'        => $subtotal,
            'shipping'        => $shipping,
            'total'           => $total,
            'status'          => 'processing',
            'notes'           => $request->notes,
        ]);

        // ── Save Order Items ──
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item->product_id,
                'vendor_id'    => $item->product->vendor_id,
                'product_name' => $item->product->name,
                'price'        => $item->product->sale_price ?? $item->product->price,
                'quantity'     => $item->quantity,
                'subtotal'     => ($item->product->sale_price ?? $item->product->price) * $item->quantity,
            ]);

            // Reduce stock
            $item->product->decrement('stock', $item->quantity);
        }

        // ── Clear Cart ──
        $user->cart()->delete();

        return redirect()->route('customer.order.success', $order->order_number)
            ->with('success', '🎉 Order placed successfully!');
    }

    // ── Order Success Page ────────────────────────────
    public function success(string $orderNumber)
    {
        $order = Order::with('items.product')
            ->where('order_number', $orderNumber)
            ->where('user_id', auth('web')->id())
            ->firstOrFail();

        return view('customer.pages.order-success', compact('order'));
    }
}
