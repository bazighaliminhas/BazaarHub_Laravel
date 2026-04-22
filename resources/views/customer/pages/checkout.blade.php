@extends('layouts.app')
@section('title', 'Checkout — BazaarHub')

@push('styles')
<style>
    .checkout-card {
        background: white; border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.07);
        border: none; overflow: hidden;
    }
    .checkout-header {
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        color: white; padding: 18px 24px;
    }
    .checkout-header h5 { margin: 0; font-weight: 700; font-size: 16px; }
    .checkout-body { padding: 24px; }

    .form-control, .form-select {
        border: 2px solid #f0f0f0; border-radius: 12px;
        padding: 11px 14px; font-size: 14px; transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #FF6B35;
        box-shadow: 0 0 0 3px rgba(255,107,53,0.1);
    }
    .form-label { font-weight: 600; font-size: 14px; color: #2C3E50; }

    /* Steps */
    .steps-bar {
        display: flex; align-items: center;
        justify-content: center; gap: 0;
        margin-bottom: 32px;
    }
    .step {
        display: flex; flex-direction: column;
        align-items: center; gap: 6px;
        flex: 1; position: relative;
    }
    .step::after {
        content: '';
        position: absolute; top: 18px; left: 60%;
        width: 80%; height: 2px;
        background: #eee; z-index: 0;
    }
    .step:last-child::after { display: none; }
    .step-circle {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 14px; z-index: 1;
        background: #f0f0f0; color: #bbb;
        transition: all 0.3s;
    }
    .step.active .step-circle {
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        color: white;
        box-shadow: 0 4px 15px rgba(255,107,53,0.4);
    }
    .step.done .step-circle {
        background: #27ae60; color: white;
    }
    .step-label { font-size: 12px; font-weight: 600; color: #bbb; }
    .step.active .step-label { color: #FF6B35; }
    .step.done .step-label   { color: #27ae60; }

    /* KuickPay Section */
    .kuickpay-box {
        border: 2px solid #f0f0f0; border-radius: 16px;
        padding: 20px; background: #fafafa;
        transition: border-color 0.3s;
    }
    .kuickpay-box.verified {
        border-color: #27ae60;
        background: #f0faf4;
    }
    .kuickpay-logo {
        background: linear-gradient(135deg, #1a1f2e, #2C3E50);
        color: white; border-radius: 10px;
        padding: 6px 14px; font-weight: 700;
        font-size: 14px; display: inline-block;
        margin-bottom: 12px;
    }
    .btn-verify {
        background: linear-gradient(135deg, #1a1f2e, #2C3E50);
        color: white; border: none; border-radius: 10px;
        padding: 10px 20px; font-weight: 600; font-size: 13px;
        transition: all 0.2s; white-space: nowrap;
    }
    .btn-verify:hover {
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        color: white;
    }
    .bill-details {
        display: none;
        background: white; border-radius: 12px;
        padding: 16px; margin-top: 14px;
        border: 1px solid #e0f0e8;
    }
    .bill-row {
        display: flex; justify-content: space-between;
        align-items: center; padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
        font-size: 14px;
    }
    .bill-row:last-child { border-bottom: none; }
    .bill-label { color: #888; }
    .bill-value { font-weight: 600; color: #2C3E50; }

    /* Order Summary */
    .summary-item {
        display: flex; justify-content: space-between;
        padding: 10px 0; border-bottom: 1px solid #f5f5f5;
        font-size: 14px;
    }
    .summary-item:last-child { border-bottom: none; }
    .summary-total {
        display: flex; justify-content: space-between;
        padding: 14px 0 0; font-size: 18px; font-weight: 700;
        color: #FF6B35;
    }

    .btn-place-order {
        background: linear-gradient(135deg, #FF6B35, #FF8C42);
        color: white; border: none; border-radius: 14px;
        padding: 14px; font-size: 16px; font-weight: 700;
        width: 100%; transition: all 0.3s;
        box-shadow: 0 6px 20px rgba(255,107,53,0.35);
    }
    .btn-place-order:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(255,107,53,0.5);
        color: white;
    }
    .btn-place-order:disabled {
        opacity: 0.6; cursor: not-allowed; transform: none;
    }

    .secure-badge {
        display: flex; align-items: center; gap: 8px;
        background: #f0faf4; border-radius: 10px;
        padding: 10px 14px; margin-top: 12px;
        font-size: 12px; color: #27ae60; font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container py-5">

    {{-- Steps --}}
    <div class="steps-bar">
        <div class="step done">
            <div class="step-circle"><i class="bi bi-cart-check"></i></div>
            <div class="step-label">Cart</div>
        </div>
        <div class="step active">
            <div class="step-circle">2</div>
            <div class="step-label">Checkout</div>
        </div>
        <div class="step">
            <div class="step-circle">3</div>
            <div class="step-label">Payment</div>
        </div>
        <div class="step">
            <div class="step-circle"><i class="bi bi-check-lg"></i></div>
            <div class="step-label">Done</div>
        </div>
    </div>

    <form action="{{ route('customer.checkout.pay') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-4">

            {{-- LEFT — Shipping + Payment --}}
            <div class="col-lg-7">

                {{-- Shipping Details --}}
                <div class="checkout-card mb-4">
                    <div class="checkout-header">
                        <h5><i class="bi bi-geo-alt me-2"></i>Shipping Information</h5>
                    </div>
                    <div class="checkout-body">
                        @if($errors->any())
                        <div class="alert border-0 rounded-3 mb-4"
                             style="background:#fdecea;color:#e74c3c;font-size:14px;">
                            @foreach($errors->all() as $e)
                                <div><i class="bi bi-x-circle me-1"></i>{{ $e }}</div>
                            @endforeach
                        </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="full_name" class="form-control"
                                       value="{{ old('full_name', auth('web')->user()->name) }}"
                                       placeholder="Muhammad Ali" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone Number *</label>
                                <input type="text" name="phone" class="form-control"
                                       value="{{ old('phone') }}"
                                       placeholder="03XX-XXXXXXX" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Delivery Address *</label>
                                <input type="text" name="address" class="form-control"
                                       value="{{ old('address') }}"
                                       placeholder="House #, Street, Area" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">City *</label>
                                <select name="city" class="form-select" required>
                                    <option value="">Select City</option>
                                    @foreach(['Karachi','Lahore','Islamabad','Rawalpindi','Faisalabad',
                                              'Multan','Peshawar','Quetta','Sialkot','Gujranwala'] as $city)
                                    <option value="{{ $city }}"
                                        {{ old('city') == $city ? 'selected' : '' }}>
                                        {{ $city }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Order Notes</label>
                                <input type="text" name="notes" class="form-control"
                                       value="{{ old('notes') }}"
                                       placeholder="Special instructions (optional)">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KuickPay Payment --}}
                <div class="checkout-card">
                    <div class="checkout-header">
                        <h5><i class="bi bi-credit-card me-2"></i>Payment via KuickPay</h5>
                    </div>
                    <div class="checkout-body">

                        <div class="kuickpay-box" id="kuickpayBox">
                            <div class="kuickpay-logo">⚡ KuickPay</div>
                            <p class="text-muted mb-3" style="font-size:13px;">
                                Enter your KuickPay Consumer Number to verify and pay securely.
                            </p>

                            <div class="d-flex gap-2">
                                <input type="text"
                                       name="consumer_number"
                                       id="consumerNumber"
                                       class="form-control"
                                       placeholder="e.g. 0000812345"
                                       required>
                                <button type="button" class="btn-verify" id="verifyBtn"
                                        onclick="verifyBill()">
                                    <i class="bi bi-search me-1"></i> Verify
                                </button>
                            </div>

                            {{-- Bill Details (shown after inquiry) --}}
                            <div class="bill-details" id="billDetails">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                                    <span class="fw-bold text-success">Bill Verified Successfully!</span>
                                </div>
                                <div class="bill-row">
                                    <span class="bill-label">Consumer Name</span>
                                    <span class="bill-value" id="billConsumerName">—</span>
                                </div>
                                <div class="bill-row">
                                    <span class="bill-label">Due Date</span>
                                    <span class="bill-value" id="billDueDate">—</span>
                                </div>
                                <div class="bill-row">
                                    <span class="bill-label">Amount Due</span>
                                    <span class="bill-value" id="billAmount" style="color:#FF6B35;">—</span>
                                </div>
                                <div class="bill-row">
                                    <span class="bill-label">Status</span>
                                    <span class="bill-value" id="billStatus">—</span>
                                </div>
                            </div>

                            {{-- Error Box --}}
                            <div id="inquiryError"
                                 style="display:none; background:#fdecea; color:#e74c3c;
                                        border-radius:10px; padding:12px 14px;
                                        margin-top:12px; font-size:13px;">
                            </div>
                        </div>

                        <div class="secure-badge">
                            <i class="bi bi-shield-lock-fill fs-5"></i>
                            <div>
                                <div>256-bit SSL Encrypted Payment</div>
                                <div style="font-weight:400;color:#555;">
                                    Your payment is secured by KuickPay BPS
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- RIGHT — Order Summary --}}
            <div class="col-lg-5">
                <div class="checkout-card" style="position:sticky;top:90px;">
                    <div class="checkout-header">
                        <h5><i class="bi bi-receipt me-2"></i>Order Summary</h5>
                    </div>
                    <div class="checkout-body">

                        {{-- Cart Items --}}
                        <div class="mb-3">
                            @foreach($cartItems as $item)
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <img src="{{ $item->product->thumbnail_url }}"
                                     style="width:52px;height:52px;object-fit:cover;
                                            border-radius:10px;flex-shrink:0;">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold" style="font-size:13px;">
                                        {{ Str::limit($item->product->name, 30) }}
                                    </div>
                                    <div style="font-size:12px;color:#888;">
                                        Qty: {{ $item->quantity }}
                                        × Rs. {{ number_format($item->product->sale_price ?? $item->product->price, 0) }}
                                    </div>
                                </div>
                                <div class="fw-bold" style="color:#FF6B35;font-size:14px;">
                                    Rs. {{ number_format(($item->product->sale_price ?? $item->product->price) * $item->quantity, 0) }}
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <hr>

                        {{-- Totals --}}
                        <div class="summary-item">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold">Rs. {{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div class="summary-item">
                            <span class="text-muted">Shipping</span>
                            <span class="fw-semibold">Rs. {{ number_format($shipping, 0) }}</span>
                        </div>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>Rs. {{ number_format($total, 0) }}</span>
                        </div>

                        <input type="hidden" name="total_amount" value="{{ $total }}">

                        <button type="submit" class="btn-place-order mt-4" id="placeOrderBtn">
                            <i class="bi bi-bag-check me-2"></i>
                            Place Order — Rs. {{ number_format($total, 0) }}
                        </button>

                        <a href="{{ route('customer.cart') }}"
                           class="btn btn-light w-100 rounded-3 mt-2 fw-semibold">
                            ← Back to Cart
                        </a>

                        {{-- Payment Logos --}}
                        <div class="text-center mt-3">
                            <div style="font-size:11px;color:#bbb;margin-bottom:8px;">
                                Secured Payment By
                            </div>
                            <div style="background:#1a1f2e;border-radius:8px;
                                        padding:8px 16px;display:inline-block;
                                        color:white;font-weight:700;font-size:13px;">
                                ⚡ KuickPay BPS
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function verifyBill() {
    const consumerNumber = document.getElementById('consumerNumber').value.trim();
    const verifyBtn      = document.getElementById('verifyBtn');
    const billDetails    = document.getElementById('billDetails');
    const errorBox       = document.getElementById('inquiryError');
    const kuickpayBox    = document.getElementById('kuickpayBox');

    if (!consumerNumber) {
        showError('Please enter your Consumer Number.');
        return;
    }

    // Loading state
    verifyBtn.disabled   = true;
    verifyBtn.innerHTML  = '<span class="spinner-border spinner-border-sm me-1"></span> Verifying...';
    billDetails.style.display = 'none';
    errorBox.style.display    = 'none';

    fetch('{{ route("customer.checkout.inquiry") }}', {
        method: 'POST',
        headers: {
            'Content-Type':  'application/json',
            'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ consumer_number: consumerNumber })
    })
    .then(r => r.json())
    .then(data => {
        verifyBtn.disabled  = false;
        verifyBtn.innerHTML = '<i class="bi bi-search me-1"></i> Verify';

        if (data.success) {
            const d = data.data;

            // Populate bill details
            document.getElementById('billConsumerName').textContent =
                d.consumer_Detail || 'N/A';

            document.getElementById('billDueDate').textContent =
                d.due_date
                    ? d.due_date.substring(0,4) + '-' +
                      d.due_date.substring(4,6) + '-' +
                      d.due_date.substring(6,8)
                    : 'N/A';

            // Format amount (last 2 digits are minor units)
            const rawAmt = d.amount_within_dueDate || '0';
            const cleanAmt = rawAmt.replace(/[^0-9]/g, '');
            const formatted = cleanAmt.length > 2
                ? 'Rs. ' + parseInt(cleanAmt.slice(0, -2)).toLocaleString()
                : 'Rs. 0';
            document.getElementById('billAmount').textContent = formatted;

            const statusMap = {
                'U': '✅ Unpaid — Ready to Pay',
                'P': '✔️ Already Paid',
                'B': '🚫 Blocked',
                'T': '🔄 Top-up Voucher',
            };
            document.getElementById('billStatus').textContent =
                statusMap[d.bill_status] || d.bill_status || 'Unknown';

            billDetails.style.display = 'block';
            kuickpayBox.classList.add('verified');

            if (data.demo) {
                showInfo('⚠️ Running in demo mode — payment will be simulated.');
            }
        } else {
            showError('❌ ' + (data.message || 'Verification failed. Please try again.'));
        }
    })
    .catch(() => {
        verifyBtn.disabled  = false;
        verifyBtn.innerHTML = '<i class="bi bi-search me-1"></i> Verify';
        showError('❌ Network error. Please check your connection.');
    });
}

function showError(msg) {
    const box = document.getElementById('inquiryError');
    box.innerHTML = '<i class="bi bi-exclamation-circle me-2"></i>' + msg;
    box.style.display = 'block';
    box.style.background = '#fdecea';
    box.style.color = '#e74c3c';
}

function showInfo(msg) {
    const box = document.getElementById('inquiryError');
    box.innerHTML = '<i class="bi bi-info-circle me-2"></i>' + msg;
    box.style.display = 'block';
    box.style.background = '#fef9e7';
    box.style.color = '#f39c12';
}

// Confirm before placing order
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('placeOrderBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing Payment...';
});
</script>
@endpush
