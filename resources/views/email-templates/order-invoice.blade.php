<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice</title>
</head>

<body style="margin:0;padding:0;background-color:#f4f6f8;font-family:Arial,Helvetica,sans-serif;">

@php
    $currencyCode = $order['bring_change_amount_currency'] ?? 'AED';

    $currency = match ($currencyCode) {
        'AED' => 'إ.د',
        'INR' => '₹',
        'USD' => '$',
        'EUR' => '€',
        default => $currencyCode
    };

    $billing  = $order['billing_address_data'] ?? null;
    $shipping = $order['shipping_address_data'] ?? null;

    $customerName  = $billing->contact_person_name ?? 'Customer';
    $customerPhone = $billing->phone ?? '';
@endphp

<!-- OUTER WRAPPER -->
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8;padding:30px 0;">
<tr>
<td align="center">

<!-- EMAIL CONTAINER -->
<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:6px;overflow:hidden;">

    <!-- HEADER -->
    <tr>
        <td style="padding:24px 30px;border-bottom:1px solid #e5e7eb;">
            <table width="100%">
                <tr>
                    <td>
                        <h2 style="margin:0;font-size:22px;color:#111;">INVOICE</h2>
                        <p style="margin:6px 0 0;font-size:12px;color:#6b7280;">
                            Invoice Date: {{ date('M d, Y', strtotime($order['created_at'])) }}<br>
                            Order #: {{ $order['id'] }}
                        </p>
                    </td>
                    <td align="right">
                        <p style="margin:0;font-size:18px;font-weight:bold;color:#1455AC;">
                            Invoice of <small>( {{ $currencyCode }} )</small><br> {{ number_format($order['order_amount'],2) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- PAYMENT -->
    <tr>
        <td style="padding:20px 30px;">
            <p style="margin:0;font-size:12px;color:#6b7280;">PAYMENT METHOD</p>
            <p style="margin:4px 0 0;font-size:14px;color:#111;">Cash On Delivery</p>
        </td>
    </tr>

    <!-- ADDRESSES -->
    <tr>
        <td style="padding:0 30px 20px 30px;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="50%" style="padding-right:10px;">
                        <p style="margin:0 0 6px;font-size:12px;color:#6b7280;">BILLED TO</p>
                        <div style="border:1px solid #e5e7eb;padding:12px;font-size:13px;line-height:18px;">
                            {{ $customerName }}<br>
                            {{ $customerPhone }}<br>
                            {{ $billing->address ?? '' }}<br>
                            {{ $billing->city ?? '' }} {{ $billing->zip ?? '' }}
                        </div>
                    </td>
                    <td width="50%" style="padding-left:10px;">
                        <p style="margin:0 0 6px;font-size:12px;color:#6b7280;">SHIPPING TO</p>
                        <div style="border:1px solid #e5e7eb;padding:12px;font-size:13px;line-height:18px;">
                            {{ $customerName }}<br>
                            {{ $customerPhone }}<br>
                            {{ $shipping->address ?? '' }}<br>
                            {{ $shipping->city ?? '' }} {{ $shipping->zip ?? '' }}
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- ITEMS TABLE -->
    <tr>
        <td style="padding:0 30px 20px 30px;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <thead>
                    <tr style="background:#f1f5f9;">
                        <th align="left" style="padding:10px;font-size:12px;border-bottom:1px solid #e5e7eb;">Item Description</th>
                        <th align="center" style="padding:10px;font-size:12px;border-bottom:1px solid #e5e7eb;">Qty</th>
                        <th align="right" style="padding:10px;font-size:12px;border-bottom:1px solid #e5e7eb;">Unit Price</th>
                        <th align="right" style="padding:10px;font-size:12px;border-bottom:1px solid #e5e7eb;">Discount</th>
                        <th align="right" style="padding:10px;font-size:12px;border-bottom:1px solid #e5e7eb;">Total</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    @php
                        $variationText = '';

                        if (!empty($item['variation'])) {

                            // If variation is JSON string
                            if (is_string($item['variation'])) {
                                $decoded = json_decode($item['variation'], true);
                                if (is_array($decoded)) {
                                    foreach ($decoded as $k => $v) {
                                        $variationText .= $k . ': ' . $v . ', ';
                                    }
                                }
                            }

                            // If variation is array
                            elseif (is_array($item['variation'])) {
                                foreach ($item['variation'] as $k => $v) {
                                    $variationText .= $k . ': ' . $v . ', ';
                                }
                            }
                        }

                        // Fallback to variant field
                        if (!$variationText && !empty($item['variant'])) {
                            $variationText = 'Variant: ' . $item['variant'];
                        }

                        $variationText = rtrim($variationText, ', ');
                    @endphp
                    <tr>
                        <td style="padding:10px;border-bottom:1px solid #e5e7eb;font-size:13px;">
                            {{ $item['product']['name'] ?? '' }}
                            @if($variationText)
                                <br>
                                <small style="color:#6b7280;">
                                    {{ $variationText }}
                                </small>
                            @endif
                        </td>
                        <td align="center" style="padding:10px;border-bottom:1px solid #e5e7eb;">{{ $item['qty'] }}</td>
                        <td align="right" style="padding:10px;border-bottom:1px solid #e5e7eb;">
                            {{ number_format($item['price'],2) }}
                        </td>
                        <td align="right" style="padding:10px;border-bottom:1px solid #e5e7eb;">
                            {{ number_format($item['discount'],2) }}
                        </td>
                        <td align="right" style="padding:10px;border-bottom:1px solid #e5e7eb;">
                            {{ number_format($item['price'] * $item['qty'],2) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>

    <!-- TOTALS -->
    <tr>
        <td style="padding:0 30px 20px 30px;">
            <table width="100%">
                <tr class="mb-2">
                    <td style="font-size:13px;">Sub Total</td>
                    <td align="right" style="font-size:13px;">
                        {{ number_format($order['order_amount'] - $order['shipping_cost'],2) }} {{ $currency }}
                    </td>
                </tr>
                <tr class="mb-2">
                    <td style="font-size:13px;">Shipping</td>
                    <td align="right" style="font-size:13px;">
                        {{ number_format($order['shipping_cost'],2) }} {{ $currency }}
                    </td>
                </tr>
                <tr>
                    <td style="font-size:15px;font-weight:bold;">Total</td>
                    <td align="right" style="font-size:15px;font-weight:bold;">
                        {{ number_format($order['order_amount'],2) }} {{ $currency }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <!-- FOOTER -->
    <tr>
        <td style="padding:20px 30px;border-top:1px solid #e5e7eb;text-align:center;font-size:12px;color:#6b7280;">
            Thanks for the purchase.<br><br>
            © {{ date('Y') }} {{ getWebConfig(name:'company_name') }}
        </td>
    </tr>

</table>
<!-- END CONTAINER -->

</td>
</tr>
</table>
<!-- END WRAPPER -->

</body>
</html>
