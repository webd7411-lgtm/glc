<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $sale->invoice_no }}</title>
    <!-- Bootstrap 5 -->
    <link href="{{ asset('assets/vendors/bootstrap5/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        :root {
            --glc-red: #d1381b;
            --glc-red-dark: #b82c12;
            --glc-banner-bg: #d84315;
            --glc-border: #d1381b;
            --glc-border-light: rgba(209, 56, 27, 0.4);
            --text-dark: #1f2428;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background-color: #525659;
            color: var(--text-dark);
            font-family: 'Segoe UI', Arial, Helvetica, sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 20px 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* ── Action Buttons (Screen Only) ── */
        .action-bar {
            position: fixed;
            top: 15px;
            right: 25px;
            z-index: 9999;
            display: flex;
            gap: 10px;
        }

        .action-bar .btn {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* ── A4 Invoice Container ── */
        .invoice-page {
            width: 210mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 10mm 14mm 10mm 14mm;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.35);
            position: relative;
        }

        /* ── Header ── */
        .invoice-header {
            margin-bottom: 6px;
        }

        .header-top-grid {
            display: grid;
            grid-template-columns: 28% 44% 28%;
            align-items: center;
            margin-bottom: 6px;
        }

        .header-contacts-left {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.4;
            color: var(--glc-red);
            text-align: left;
        }

        .header-contacts-right {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.4;
            color: var(--glc-red);
            text-align: right;
        }

        .header-center-box {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .logo-emblem {
            display: inline-block;
            margin-bottom: 1px;
        }

        .logo-svg {
            width: 175px;
            height: 62px;
            display: block;
        }

        .brand-title {
            font-size: 21px;
            font-weight: 800;
            color: var(--glc-red);
            margin-top: 1px;
            letter-spacing: 0.5px;
            line-height: 1.15;
            font-family: Arial, Helvetica, sans-serif;
        }

        .brand-taglines {
            font-size: 11px;
            font-weight: 700;
            color: var(--glc-red);
            line-height: 1.35;
            margin-top: 3px;
        }

        /* ── Metadata Row (No, Date, M/S) ── */
        .meta-section {
            margin-top: 8px;
            margin-bottom: 8px;
            color: var(--glc-red);
            font-weight: 700;
            font-size: 14px;
        }

        .meta-row-1 {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 8px;
        }

        .meta-field {
            display: inline-flex;
            align-items: baseline;
            gap: 6px;
        }

        .meta-label {
            font-weight: 800;
            color: var(--glc-red);
            font-size: 15px;
        }

        .meta-fill-line {
            display: inline-block;
            border-bottom: 1.5px solid #222;
            color: #111;
            font-weight: 700;
            padding: 0 6px 1px 6px;
            font-size: 14px;
        }

        .meta-fill-line.inv-no {
            min-width: 160px;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #111;
        }

        .meta-fill-line.inv-date {
            min-width: 160px;
            text-align: center;
        }

        .meta-row-2 {
            display: flex;
            align-items: baseline;
            gap: 8px;
        }

        .meta-fill-line.inv-customer {
            flex: 1;
            padding-left: 8px;
            font-size: 14.5px;
        }

        /* ── Main Table with Watermark ── */
        .table-wrapper {
            position: relative;
            flex-grow: 1;
            margin-bottom: 10px;
        }

        .watermark-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 75%;
            opacity: 0.08;
            pointer-events: none;
            z-index: 1;
            text-align: center;
        }

        .glc-invoice-table {
            width: 100%;
            border-collapse: collapse;
            position: relative;
            z-index: 2;
            border: 1.5px solid var(--glc-border);
        }

        .glc-invoice-table thead th {
            border: 1.5px solid var(--glc-border);
            padding: 6px 8px;
            color: var(--glc-red);
            font-weight: 800;
            font-size: 13.5px;
            text-align: center;
            background: #ffffff;
            letter-spacing: 0.5px;
        }

        .glc-invoice-table tbody td {
            border: 1px solid var(--glc-border-light);
            border-left: 1.5px solid var(--glc-border);
            border-right: 1.5px solid var(--glc-border);
            padding: 4px 8px;
            font-size: 12.5px;
            height: 25px;
            vertical-align: middle;
            background: transparent;
            color: #111;
        }

        /* Column Specifics */
        .col-sr {
            width: 7%;
            text-align: center;
            color: var(--glc-red);
            font-weight: 700;
        }

        .col-particulars {
            width: 53%;
            text-align: left;
        }

        .col-qty {
            width: 12%;
            text-align: center;
            font-weight: 600;
        }

        .col-rate {
            width: 13%;
            text-align: right;
            padding-right: 10px !important;
        }

        .col-amount {
            width: 15%;
            text-align: right;
            padding-right: 10px !important;
            font-weight: 700;
        }

        .item-title {
            font-weight: 700;
            font-size: 13px;
            color: #000;
        }

        .item-details {
            font-size: 11px;
            color: #555;
            margin-top: 1px;
        }

        /* ── Footer Section ── */
        .footer-section {
            margin-top: 6px;
            margin-bottom: 8px;
        }

        .footer-flex-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
        }

        /* Note Box on Left */
        .note-box {
            flex: 1;
            max-width: 55%;
            font-size: 12px;
            color: #222;
        }

        .note-heading {
            font-weight: 800;
            font-size: 13px;
            color: #222;
            margin-bottom: 2px;
        }

        .note-list-custom {
            margin: 0;
            padding-left: 2px;
            line-height: 1.45;
            font-weight: 600;
            font-size: 12px;
            color: #222;
        }

        .note-list-custom div {
            margin-bottom: 1.5px;
        }

        .extra-notes {
            margin-top: 8px;
            padding: 5px 8px;
            background-color: #f8f9fa;
            border-left: 3px solid var(--glc-red);
            font-size: 11.5px;
            font-style: italic;
        }

        /* Summary Box on Right */
        .summary-box {
            width: 42%;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            font-size: 13px;
            padding: 2px 0;
        }

        .summary-row.main-total {
            font-size: 15px;
            font-weight: 800;
            color: var(--glc-red);
            border-bottom: 1.5px solid var(--glc-border);
            padding-bottom: 4px;
            margin-bottom: 4px;
        }

        .summary-row.main-total .sum-val {
            border-bottom: 1.5px solid #222;
            min-width: 130px;
            text-align: right;
            padding-right: 4px;
            color: #111;
        }

        .sum-lbl {
            font-weight: 700;
        }

        .sum-val {
            font-weight: 700;
            text-align: right;
            color: #111;
        }

        .sum-val.negative {
            color: #c62828;
        }

        .sum-val.paid {
            color: #2e7d32;
        }

        .summary-row.closing-row {
            font-weight: 800;
            background-color: #fff3e0;
            padding: 4px 6px;
            border-radius: 3px;
            border: 1px solid #ffe0b2;
            margin-top: 3px;
        }

        .signature-container {
            margin-top: 20px;
            display: flex;
            align-items: baseline;
            justify-content: flex-end;
            gap: 10px;
        }

        .signature-label {
            font-weight: 700;
            font-size: 14px;
            color: var(--glc-red);
        }

        .signature-line {
            display: inline-block;
            border-bottom: 1.5px solid #222;
            width: 160px;
        }

        /* ── Full Width Bottom Banner ── */
        .bottom-banner {
            background-color: var(--glc-banner-bg) !important;
            color: #ffffff !important;
            text-align: center;
            padding: 7px 12px;
            font-size: 12.5px;
            font-weight: 700;
            line-height: 1.45;
            letter-spacing: 0.2px;
            margin-top: 25px;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .developer-credit {
            text-align: center;
            font-size: 9.5px;
            font-weight: 600;
            color: #475569;
            margin-top: 6px;
            line-height: 1.35;
            letter-spacing: 0.2px;
        }

        /* ── Print Media Query ── */
        @page {
            size: A4 portrait;
            margin: 8mm 10mm 8mm 10mm;
        }

        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                color: #000 !important;
            }

            .no-print {
                display: none !important;
            }

            .invoice-page {
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border: none !important;
            }

            .watermark-overlay {
                opacity: 0.08 !important;
            }

            .bottom-banner {
                background-color: #d84315 !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>

    <!-- Floating Top Action Buttons -->
    <div class="action-bar no-print">
        <button onclick="window.print()" class="btn btn-primary btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-printer-fill" viewBox="0 0 16 16">
                <path d="M0 9a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V9zm4-6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2H4V3z" />
                <path d="M2.5 14.5A1.5 1.5 0 0 1 1 13V9a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v4a1.5 1.5 0 0 1-1.5 1.5h-13z" />
            </svg>
            Print Invoice
        </button>
        <a href="{{ route('sales.receipt', $sale->id) }}" class="btn btn-warning btn-sm text-dark">
            Thermal Receipt
        </a>
        <a href="{{ route('sale.index') }}" class="btn btn-secondary btn-sm">
            Back to Sales
        </a>
    </div>

    @php
        $companyLogo = \App\Models\Setting::get('company_logo') ?: \App\Models\Setting::get('web_site_logo');
        $leftContacts = \App\Models\Setting::get('invoice_header_contacts_left', "Faisal Mehmood\n0325 4682466\nFurqaan Shakeel\n03044576200");
        $rightContacts = \App\Models\Setting::get('invoice_header_contacts_right', "Anjum Shakeel\n03008033184\nTayyab Shakeel\n03054453139\nPTCL #: 042 37322272\n042 37232370");
        $companyName = \App\Models\Setting::get('invoice_company_name') ?: \App\Models\Setting::get('company_name', 'Long Life Accessories');
        $invoiceOnlineLink = \App\Models\Setting::get('invoice_online_link');
        $headerTagline = \App\Models\Setting::get('invoice_header_tagline', "Power Bank, Battery, Data Cable, Charger, Head Phone\nHandsfree, Bluetooth & Multimedia");
        $companyAddress = \App\Models\Setting::get('company_address', "Branch#1: Shop #22,45, Basement Madina Center The Mall Hall Road, Lhr.\nBranch#2: Shop #40,43, 3rd Floor Hassan Center Hall Road, Lhr.");
        $invoiceTerms = \App\Models\Setting::get('invoice_terms', "1. No Return no Exchange without bill\n2. Change of Mind is not accepted\n3. Damaged or Burned not Accepted");

        // Calculations
        $grossTotal  = collect($saleItems)->sum('total');
        $itemDiscounts = collect($saleItems)->sum('discount_amount');
        $extraDiscount = (float)($sale->total_extradiscount ?? 0);
        $totalDisc = $itemDiscounts + $extraDiscount;
        $netBill = (float)($sale->total_net ?? ($grossTotal - $extraDiscount));
        $paidAmount = (float)($sale->cash ?? 0);

        // Exchange return if any
        $exchangeReturn = \App\Models\SaleReturn::with('items.product')->where('remarks', 'LIKE', '%Invoice #'.$sale->invoice_no.'%')->first();
        $exchangeReturnedAmount = 0;
        if ($exchangeReturn) {
            $exchangeReturnedAmount = $exchangeReturn->items->sum('line_total');
        }
        $finalPayable = $netBill - $exchangeReturnedAmount;
        $finalBal = $previousBalance + $finalPayable - $paidAmount;

        // Ensure minimum 16 table rows for authentic invoice pad look
        $totalItemsCount = count($saleItems);
        $minRows = 16;
        $rowsNeeded = max($minRows, $totalItemsCount);
    @endphp

    <div class="invoice-page">
        <!-- Header Section -->
            <div class="invoice-header">
                <div class="header-top-grid">
                    <!-- Left Contacts -->
                    <div class="header-contacts-left">
                        {!! nl2br(e($leftContacts)) !!}
                    </div>

                    <!-- Center: Oval Logo & Brand Tagline -->
                    <div class="header-center-box">
                        <div class="logo-emblem">
                            @if(!empty($companyLogo))
                                <img src="{{ asset(ltrim($companyLogo, '/')) }}" alt="Logo" class="logo-img" style="max-height: 65px; max-width: 220px; object-fit: contain; display: block; margin: 0 auto;">
                            @else
                                <svg class="logo-svg" viewBox="0 0 250 88">
                                    <ellipse cx="125" cy="44" rx="116" ry="38" fill="none" stroke="#d1381b" stroke-width="4.5" />
                                    <ellipse cx="125" cy="44" rx="110" ry="33" fill="none" stroke="#d1381b" stroke-width="1.8" />
                                    <text x="122" y="58" text-anchor="middle" font-family="'Arial Black', Impact, Arial, sans-serif" font-weight="900" font-size="46" fill="#d1381b" letter-spacing="1">GLC</text>
                                    <text x="210" y="27" font-family="Arial, sans-serif" font-size="14" font-weight="bold" fill="#d1381b">®</text>
                                </svg>
                            @endif
                        </div>
                        <div class="brand-title">{{ $companyName }}</div>
                        @if(!empty($headerTagline))
                            <div class="brand-taglines">
                                {!! nl2br(e($headerTagline)) !!}
                            </div>
                        @endif
                        @if(!empty($invoiceOnlineLink))
                            <div class="brand-link" style="font-size: 10px; font-weight: 700; color: #d1381b; margin-top: 2px;">
                                {{ $invoiceOnlineLink }}
                            </div>
                        @endif
                    </div>

                    <!-- Right Contacts -->
                    <div class="header-contacts-right">
                        {!! nl2br(e($rightContacts)) !!}
                    </div>
                </div>
            </div>

            <!-- Metadata Section (No, Date, M/S) -->
            <div class="meta-section">
                <div class="meta-row-1">
                    <div class="meta-field">
                        <span class="meta-label">No:</span>
                        <span class="meta-fill-line inv-no">{{ $sale->invoice_no }}</span>
                    </div>
                    <div class="meta-field">
                        <span class="meta-label">Date:</span>
                        <span class="meta-fill-line inv-date">
                            {{ $sale->created_at ? $sale->created_at->format('d/m/Y') : date('d/m/Y') }}
                        </span>
                    </div>
                </div>
                <div class="meta-row-2">
                    <span class="meta-label">M/S:</span>
                    <span class="meta-fill-line inv-customer">
                        {{ $sale->walkin_name ?? ($sale->customer_relation->customer_name ?? 'Walking Customer') }}
                        @if(!empty($sale->customer_relation?->mobile))
                            &nbsp; (Mob: {{ $sale->customer_relation->mobile }})
                        @endif
                        @if(!empty($sale->customer_relation?->address))
                            &nbsp; - {{ $sale->customer_relation->address }}
                        @endif
                    </span>
                </div>
            </div>

            <!-- Items Table Section -->
            <div class="table-wrapper">
                <!-- Watermark -->
                <div class="watermark-overlay">
                    @if(!empty($companyLogo))
                        <img src="{{ asset(ltrim($companyLogo, '/')) }}" alt="Watermark" style="max-width: 320px; max-height: 140px; object-fit: contain; display: block; margin: 0 auto;">
                    @else
                        <svg viewBox="0 0 320 140" style="width: 100%; height: auto;">
                            <ellipse cx="160" cy="55" rx="150" ry="50" fill="none" stroke="#d1381b" stroke-width="4.5" />
                            <ellipse cx="160" cy="55" rx="143" ry="44" fill="none" stroke="#d1381b" stroke-width="1.8" />
                            <text x="156" y="70" text-anchor="middle" font-family="'Arial Black', Impact, sans-serif" font-weight="900" font-size="54" fill="#d1381b">GLC</text>
                            <text x="265" y="32" font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="#d1381b">®</text>
                            <text x="160" y="124" text-anchor="middle" font-family="Arial, sans-serif" font-weight="bold" font-size="21" fill="#d1381b" letter-spacing="1">Long Life Accessories</text>
                        </svg>
                    @endif
                </div>

                <table class="glc-invoice-table">
                    <thead>
                        <tr>
                            <th class="col-sr">S#</th>
                            <th class="col-particulars">PARTICULARS</th>
                            <th class="col-qty">QTY.</th>
                            <th class="col-rate">RATE</th>
                            <th class="col-amount">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < $rowsNeeded; $i++)
                            @if (isset($saleItems[$i]))
                                @php
                                    $item = $saleItems[$i];
                                    $totalPieces = (float) $item['total_pieces'];
                                    $qtyDisplay = ($totalPieces == (int)$totalPieces) ? (int)$totalPieces : number_format($totalPieces, 2);
                                @endphp
                                <tr>
                                    <td class="col-sr">{{ $i + 1 }}</td>
                                    <td class="col-particulars">
                                        <div class="item-title">
                                            {{ $item['item_name'] }}
                                            @if (!empty($item['item_code']))
                                                <span class="text-muted fw-normal" style="font-size: 11px;">({{ $item['item_code'] }})</span>
                                            @endif
                                        </div>
                                        @if (!empty($item['color']) || !empty($item['size_val']))
                                            <div class="item-details">
                                                @if (!empty($item['color']))
                                                    <span>
                                                        @if(is_array($item['color']))
                                                            {{ implode(', ', $item['color']) }}
                                                        @else
                                                            {{ $item['color'] }}
                                                        @endif
                                                    </span>
                                                @endif
                                                @if (!empty($item['size_val']) && $item['size_val'] !== '-')
                                                    <span class="ms-1">| {{ $item['size_val'] }}</span>
                                                @endif
                                                @if ((int)($item['pieces_per_box'] ?? 1) > 1)
                                                    <span class="ms-1">| Pack: {{ $item['pieces_per_box'] }} pcs</span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>
                                    <td class="col-qty">{{ $qtyDisplay }}</td>
                                    <td class="col-rate">{{ number_format($item['price'], 2) }}</td>
                                    <td class="col-amount">{{ number_format($item['total'], 2) }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td class="col-sr">{{ $i + 1 }}</td>
                                    <td class="col-particulars">&nbsp;</td>
                                    <td class="col-qty">&nbsp;</td>
                                    <td class="col-rate">&nbsp;</td>
                                    <td class="col-amount">&nbsp;</td>
                                </tr>
                            @endif
                        @endfor
                    </tbody>
                </table>
            </div>

            <!-- Returned Items (Exchange) if any -->
            @if($exchangeReturn && $exchangeReturn->items->count() > 0)
            <div style="margin-bottom: 8px;">
                <div style="font-weight: 700; color: var(--glc-red); font-size: 12px; margin-bottom: 3px;">
                    Returned Items (Exchange):
                </div>
                <table class="glc-invoice-table" style="font-size: 11px;">
                    <thead>
                        <tr>
                            <th class="col-sr" style="padding: 3px;">S#</th>
                            <th class="col-particulars" style="padding: 3px;">PARTICULARS</th>
                            <th class="col-qty" style="padding: 3px;">QTY.</th>
                            <th class="col-rate" style="padding: 3px;">RATE</th>
                            <th class="col-amount" style="padding: 3px;">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($exchangeReturn->items as $retItem)
                            <tr>
                                <td class="col-sr">{{ $loop->iteration }}</td>
                                <td class="col-particulars">{{ $retItem->product->item_name ?? 'Item' }}</td>
                                <td class="col-qty">{{ (float)$retItem->qty }}</td>
                                <td class="col-rate">{{ number_format($retItem->price, 2) }}</td>
                                <td class="col-amount" style="color: #c62828;">-{{ number_format($retItem->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Footer: Note on Left, Totals & Signature on Right -->
            <div class="footer-section">
                <div class="footer-flex-row">
                    <!-- Note on Left -->
                    <div class="note-box">
                        <div class="note-heading">NOTE:</div>
                        @php
                            $noteLines = array_filter(array_map('trim', explode("\n", $invoiceTerms)));
                        @endphp
                        @if(count($noteLines) > 0)
                            <div class="note-list-custom">
                                @foreach($noteLines as $nLine)
                                    <div>{{ $nLine }}</div>
                                @endforeach
                            </div>
                        @endif

                        @if ($sale->return_note)
                            <div class="extra-notes">
                                <strong>Note:</strong> {{ $sale->return_note }}
                            </div>
                        @endif

                        @if ($sale->reference)
                            <div class="extra-notes">
                                <strong>Remarks:</strong> {{ $sale->reference }}
                            </div>
                        @endif
                    </div>

                    <!-- Financial Summary & Signature on Right -->
                    <div class="summary-box">
                        <div class="summary-row main-total">
                            <span class="sum-lbl">TOTAL:</span>
                            <span class="sum-val">{{ number_format($sale->total_net ?? $grossTotal, 2) }}</span>
                        </div>

                        <div class="signature-container">
                            <span class="signature-label">Signature</span>
                            <span class="signature-line"></span>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Full-Width Bottom Orange Banner -->
        @if(!empty($companyAddress))
        <div class="bottom-banner">
            {!! nl2br(e($companyAddress)) !!}
        </div>
        @endif

        <!-- Developer Credit -->
        <div class="developer-credit">
            <div>Powered by Prowave Technologies</div>
            <div>📞 +92 317 3836 223</div>
        </div>
    </div>

</body>

</html>
