<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Inspection Report #{{ $inspection->id }}
    </title>


    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
            background: #f5f5f5;
            font-size: 13px;
        }

        .report {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 35px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #198754;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 25px;
            color: #198754;
        }

        .header h2 {
            margin: 0 0 5px;
            font-size: 18px;
        }

        .header p {
            margin: 3px 0;
            color: #666;
        }

        .report-title {
            text-align: center;
            margin: 20px 0;
        }

        .report-title h3 {
            margin: 0;
            font-size: 20px;
        }

        .section {
            margin-top: 25px;
        }

        .section-title {
            background: #198754;
            color: white;
            padding: 9px 12px;
            font-size: 15px;
            font-weight: bold;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid #ddd;
            padding: 9px;
        }

        .info-table .label {
            font-weight: bold;
            background: #f8f9fa;
            width: 20%;
        }

        table.checklist {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        table.checklist th {
            background: #f1f3f5;
            font-weight: bold;
        }

        table.checklist th,
        table.checklist td {
            border: 1px solid #bbb;
            padding: 7px;
            vertical-align: top;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border: 1px solid #aaa;
            border-radius: 4px;
            font-size: 11px;
        }

        .assessment {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .assessment-box {
            display: table-cell;
            width: 50%;
            border: 1px solid #ddd;
            padding: 15px;
            vertical-align: top;
        }

        .assessment-box h4 {
            margin-top: 0;
            color: #198754;
        }

        .signature-area {
            margin-top: 60px;
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .signature {
            display: table-cell;
            width: 50%;
            padding-right: 40px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            height: 35px;
            margin-bottom: 8px;
        }

        .footer {
            margin-top: 40px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }

        .print-buttons {
            max-width: 1100px;
            margin: 0 auto 15px;
            display: flex;
            gap: 10px;
        }

        .print-buttons button {
            padding: 10px 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-button {
            background: #198754;
            color: white;
        }

        .close-button {
            background: #6c757d;
            color: white;
        }

        @media print {

            body {
                background: white;
                padding: 0;
            }

            .report {
                max-width: none;
                padding: 15px;
            }

            .print-buttons {
                display: none;
            }

            .section {
                page-break-inside: avoid;
            }

            table.checklist {
                page-break-inside: auto;
            }

            table.checklist tr {
                page-break-inside: avoid;
            }

        }

    </style>

</head>


<body>


    <div class="print-buttons">

        <button
            type="button"
            class="print-button"
            onclick="window.print()"
        >
            🖨 Print Report
        </button>


        <button
            type="button"
            class="close-button"
            onclick="window.close()"
        >
            Close
        </button>

    </div>


    <div class="report">


        {{-- HEADER --}}
        <div class="header">

            <h1>
                SOLAR-BATTERY MAINTENANCE SYSTEM
            </h1>

            <h2>
                Preventive Maintenance & Inspection Record
            </h2>

            <p>
                Departmental Maintenance Record
            </p>

        </div>


        <div class="report-title">

            <h3>
                SOLAR INSTALLATION INSPECTION REPORT
            </h3>

            <p>
                Inspection Reference:
                <strong>#{{ str_pad($inspection->id, 5, '0', STR_PAD_LEFT) }}</strong>
            </p>

        </div>


        {{-- INSTALLATION INFORMATION --}}
        <div class="section">

            <div class="section-title">
                1. Inspection Information
            </div>


            <table class="info-table">

                <tr>

                    <td class="label">
                        Installation
                    </td>

                    <td>
                        {{ $inspection->installation->name ?? 'N/A' }}
                    </td>


                    <td class="label">
                        Location
                    </td>

                    <td>
                        {{ $inspection->installation->location ?? 'N/A' }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Inspector
                    </td>

                    <td>
                        {{ $inspection->inspector->name ?? 'N/A' }}
                    </td>


                    <td class="label">
                        Inspection Date
                    </td>

                    <td>
                        {{ $inspection->inspection_date?->format('d M Y') }}
                    </td>

                </tr>


                <tr>

                    <td class="label">
                        Next Inspection
                    </td>

                    <td>
                        {{ $inspection->next_inspection_date?->format('d M Y') ?? 'Not set' }}
                    </td>


                    <td class="label">
                        Overall Condition
                    </td>

                    <td>
                        <strong>
                            {{ $inspection->overall_condition }}
                        </strong>
                    </td>

                </tr>

            </table>

        </div>


        {{-- CHECKLIST --}}
        <div class="section">

            <div class="section-title">
                2. Component Inspection Checklist
            </div>


            <table class="checklist">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Component</th>
                        <th>Check Item</th>
                        <th>Result</th>
                        <th>Measurement</th>
                        <th>Condition</th>
                        <th>Remarks</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse(
                        $inspection->inspectionItems
                        as $index => $item
                    )

                        <tr>

                            <td>
                                {{ $index + 1 }}
                            </td>

                            <td>

                                <strong>
                                    {{ $item->component->name ?? 'N/A' }}
                                </strong>

                                <br>

                                <small>
                                    {{ $item->component->componentType->name ?? 'Other' }}
                                </small>

                            </td>

                            <td>
                                {{ $item->check_item }}
                            </td>

                            <td>
                                {{ $item->result }}
                            </td>

                            <td>

                                @if($item->measurement !== null)

                                    {{ $item->measurement }}
                                    {{ $item->unit }}

                                @else

                                    —

                                @endif

                            </td>

                            <td>
                                {{ $item->condition }}
                            </td>

                            <td>
                                {{ $item->remarks ?: '—' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                style="text-align:center;"
                            >
                                No checklist records available.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- ASSESSMENT --}}
        <div class="section">

            <div class="section-title">
                3. Inspection Assessment
            </div>


            <div class="assessment">


                <div class="assessment-box">

                    <h4>
                        General Observation
                    </h4>

                    @if($inspection->general_observation)

                        {!! nl2br(e($inspection->general_observation)) !!}

                    @else

                        No general observation recorded.

                    @endif

                </div>


                <div class="assessment-box">

                    <h4>
                        Recommendation
                    </h4>

                    @if($inspection->recommendation)

                        {!! nl2br(e($inspection->recommendation)) !!}

                    @else

                        No recommendation recorded.

                    @endif

                </div>


            </div>

        </div>


        {{-- ADDITIONAL REMARKS --}}
        <div class="section">

            <div class="section-title">
                4. Additional Remarks
            </div>


            <div style="border:1px solid #ddd; padding:15px;">

                @if($inspection->remarks)

                    {!! nl2br(e($inspection->remarks)) !!}

                @else

                    No additional remarks.

                @endif

            </div>

        </div>


        {{-- SIGNATURE --}}
        <div class="section">

            <div class="section-title">
                5. Verification and Approval
            </div>


            <div class="signature-area">


                <div class="signature">

                    <div class="signature-line"></div>

                    <strong>
                        Inspector's Signature
                    </strong>

                    <br>

                    Date:
                    ____________________

                </div>


                <div class="signature">

                    <div class="signature-line"></div>

                    <strong>
                        Supervisor / Approving Officer
                    </strong>

                    <br>

                    Date:
                    ____________________

                </div>


            </div>

        </div>


        <div class="footer">

            Solar-Battery Preventive Maintenance and Replacement
            Decision-Support System

            <br>

            Inspection Reference:
            #{{ str_pad($inspection->id, 5, '0', STR_PAD_LEFT) }}

            |

            Generated:
            {{ now()->format('d M Y H:i') }}

        </div>


    </div>


</body>

</html>