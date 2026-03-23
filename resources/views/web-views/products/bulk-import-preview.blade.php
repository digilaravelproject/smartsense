@extends('layouts.front-end.app')

@section('title', translate('Bulk_Order_Preview'))

@push('css_or_js')
    <style>
        .quantity-btn {
            cursor: pointer;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f5f9;
            border: 1px solid #e2e2e2;
            border-radius: 50%;
            transition: all 0.2s;
            font-weight: bold;
            font-size: 18px;
            user-select: none;
            /* Prevent selection */
        }

        .quantity-btn:active {
            background: #d0d0d0;
            transform: scale(0.95);
        }

        .quantity-btn:hover {
            background: var(--web-primary);
            color: #fff;
            border-color: var(--web-primary);
        }

        .quantity-input {
            width: 60px !important;
            text-align: center;
            border: none;
            background: transparent;
            font-weight: 700;
            font-size: 16px;
            margin: 0 5px;
        }

        /* Hide arrows */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4 rtl" style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ translate('Bulk_Order_Preview') }}</h5>
                <span class="badge badge-soft-info">{{ count($foundProducts) }} {{ translate('Items_Matched') }}</span>
            </div>
            <div class="card-body">

                {{-- Debug: show parsed rows from OCR (collapsed) --}}
                @if(isset($productRows))
                    <details class="mb-3">
                        <summary class="font-weight-bold">Detected rows (debug)</summary>
                        <pre style="white-space: pre-wrap;">{{ json_encode($productRows, JSON_PRETTY_PRINT) }}</pre>
                    </details>
                @endif

                <form action="{{ route('product-bulk-import-confirm') }}" method="POST" id="bulk-import-form">
                    @csrf

                    @if (count($foundProducts) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless align-middle">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 5%">{{ translate('Select') }}</th>
                                        <th style="width: 10%">{{ translate('Image') }}</th>
                                        <th style="width: 30%">{{ translate('Product') }}</th>
                                        <th style="width: 10%" class="text-center">{{ translate('Type') }}</th>
                                        <th style="width: 15%">{{ translate('Stock') }}</th>
                                        <th style="width: 20%" class="text-center">{{ translate('Quantity') }}</th>
                                        <?php /*<th style="width: 10%" class="text-right">{{ translate('Total') }}</th>*/?>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($foundProducts as $key => $product)
                                        <tr class="border-bottom">
                                            {{-- Checkbox --}}
                                            <td class="align-middle">
                                                @if ($product->current_stock > 0 || $product->product_type == 'digital')
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input product-checkbox"
                                                            id="check-{{ $key }}"
                                                            name="products[{{ $key }}][selected]" value="1"
                                                            checked>
                                                        <label class="custom-control-label"
                                                            for="check-{{ $key }}"></label>
                                                    </div>
                                                @else
                                                    <input type="checkbox" disabled>
                                                @endif
                                                <input type="hidden" name="products[{{ $key }}][id]"
                                                    value="{{ $product->id }}">
                                            </td>

                                            {{-- Image --}}
                                            <td class="align-middle">
                                                <img src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}"
                                                    onerror="this.src='{{ asset('public/assets/front-end/img/image-place-holder.png') }}'"
                                                    class="rounded border"
                                                    style="height: 60px; width: 60px; object-fit: cover;">
                                            </td>

                                            {{-- Name & Price --}}
                                            <td class="align-middle">
                                                <a href="{{ $product->details_url }}" target="_blank"
                                                    class="text-dark font-weight-bold d-block">
                                                    {{ Str::limit($product->name, 40) }}
                                                </a>
                                                @if($product->unit_price !== '' && $product->unit_price > 0)
                                                    <span class="text-muted small">
                                                        {{ \App\Utils\Helpers::currency_converter($product->unit_price) }}
                                                    </span>
                                                @endif
                                            </td>

                                            {{-- Type --}}
                                            <td class="align-middle text-center">
                                                @if ($product->product_type == 'physical')
                                                    <span class="badge badge-soft-info">{{ translate('Physical') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-soft-warning">{{ translate('Digital') }}</span>
                                                @endif
                                            </td>

                                            {{-- Stock Status --}}
                                            <td class="align-middle">
                                                @if ($product->current_stock > 0)
                                                    <span class="text-success small font-weight-bold">
                                                        {{ $product->current_stock }} {{ translate('In_Stock') }}
                                                    </span>
                                                @elseif($product->product_type == 'digital')
                                                    <span class="text-success small">{{ translate('Unlimited') }}</span>
                                                @else
                                                    <span
                                                        class="text-danger small font-weight-bold">{{ translate('Out_of_Stock') }}</span>
                                                @endif
                                            </td>

                                            {{-- Quantity Control --}}
                                            <td class="align-middle">
                                                @if ($product->current_stock > 0 || $product->product_type == 'digital')
                                                    <div class="d-flex align-items-center justify-content-center border rounded p-1"
                                                        style="width: 130px; margin: auto;">
                                                        {{-- Minus Button --}}
                                                        <div class="quantity-btn"
                                                            onclick="modifyQty('{{ $key }}', -1)">-</div>

                                                        {{-- Input Field --}}
                                                        <input type="number"
                                                            name="products[{{ $key }}][quantity]"
                                                            id="qty-{{ $key }}"
                                                            class="form-control quantity-input" value="{{ $productQuantities[$product->id] ?? 1 }}"
                                                            min="1" {{-- Max stock set karo (Digital ke liye high limit) --}}
                                                            max="{{ $product->product_type == 'digital' ? 999 : $product->current_stock }}"
                                                            onchange="validateQty('{{ $key }}')">

                                                        {{-- Plus Button --}}
                                                        <div class="quantity-btn"
                                                            onclick="modifyQty('{{ $key }}', 1)">+</div>
                                                    </div>
                                                @else
                                                    <div class="text-center text-muted">-</div>
                                                @endif
                                            </td>

                                            {{-- Total Price Preview --}}
                                            @if($product->unit_price !== '' && $product->unit_price > 0)
                                                <td class="align-middle text-right font-weight-bold text-primary">
                                                    <span id="price-{{ $key }}"
                                                        data-unit-price="{{ $product->unit_price }}">
                                                        {{ \App\Utils\Helpers::currency_converter($product->unit_price) }}
                                                    </span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="{{ asset('public/assets/front-end/img/no-data-found.png') }}" alt="No Data"
                                style="width: 150px; opacity: 0.5;">
                            <h5 class="mt-3 text-muted">{{ translate('No_matching_products_found.') }}</h5>
                        </div>
                    @endif

                    {{-- Not Found List --}}
                    @if (count($notFoundNames) > 0)
                        <div class="mt-4 bg-light p-3 rounded">
                            <h6 class="text-danger font-weight-bold">
                                <i class="tio-warning-outlined"></i> {{ translate('Items_Not_Found') }}
                                ({{ count($notFoundNames) }})
                            </h6>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach ($notFoundNames as $name)
                                    <span class="badge badge-secondary p-2">{{ $name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 d-flex justify-content-end gap-3 pb-3">
                        <a href="{{ route('home') }}" class="btn btn-secondary px-4">{{ translate('Cancel') }}</a>
                        @if (count($foundProducts) > 0)
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="tio-shopping-cart-outlined mr-1"></i> {{ translate('Add_to_Cart_&_Checkout') }}
                            </button>
                        @endif
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict";

        // Quantity Modify Function
        function modifyQty(key, value) {
            let qtyInput = $('#qty-' + key);
            let checkBox = $('#check-' + key);

            // Current values parse karo
            let currentVal = parseInt(qtyInput.val());
            let maxVal = parseInt(qtyInput.attr('max'));
            let newVal = currentVal + value;

            // Logic check
            if (newVal < 1) {
                newVal = 1;
            } else if (newVal > maxVal) {
                newVal = maxVal;
                toastr.warning("{{ translate('Max_stock_reached') }}");
            }

            // Value update karo
            qtyInput.val(newVal);

            // Checkbox auto-check karo
            if (checkBox.length) {
                checkBox.prop('checked', true);
            }

            // Price update karo (Optional UI enhancement)
            updatePrice(key, newVal);
        }

        // Manual Input Validation
        function validateQty(key) {
            let qtyInput = $('#qty-' + key);
            let currentVal = parseInt(qtyInput.val());
            let maxVal = parseInt(qtyInput.attr('max'));

            if (isNaN(currentVal) || currentVal < 1) {
                qtyInput.val(1);
                updatePrice(key, 1);
            } else if (currentVal > maxVal) {
                qtyInput.val(maxVal);
                toastr.warning("{{ translate('Quantity_cannot_exceed_stock') }}");
                updatePrice(key, maxVal);
            } else {
                updatePrice(key, currentVal);
            }
        }

        // Live Price Update Logic (Frontend Only)
        function updatePrice(key, qty) {
            let priceSpan = $('#price-' + key);
            let unitPrice = parseFloat(priceSpan.data('unit-price'));
            let total = unitPrice * qty;

            // Currency formatting (basic) - Backend will handle actual total
            // Ye sirf UI ke liye hai
            // priceSpan.text(total.toFixed(2));
        }

        // Auto-focus & select detected OCR quantity (first occurrence)
        $(document).ready(function() {
            try {
                // find first quantity input with value > 1 (likely from OCR)
                let selector = $('.quantity-input').filter(function() {
                    let v = parseInt($(this).val());
                    return !isNaN(v) && v > 1;
                }).first();

                if (selector && selector.length) {
                    selector.focus().select();
                }
            } catch (e) {
                // no-op on errors to avoid breaking page
                console.warn('Auto-select qty failed', e);
            }
        });
    </script>
@endpush