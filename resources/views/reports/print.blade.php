<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Solar Maintenance System - Report
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            font-family:
                Arial,
                Helvetica,
                sans-serif;

            color: #222;

            background: #ffffff;

            margin: 0;

            padding: 30px;

            font-size: 13px;
        }

        .report-container {
            max-width: 1100px;

            margin: 0 auto;
        }

        .header {
            text-align: center;

            border-bottom:
                3px solid #198754;

            padding-bottom: 18px;

            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0;

            font-size: 25px;

            color: #146c43;
        }

        .header h2 {
            margin: 7px 0;

            font-size: 18px;
        }

        .header p {
            margin: 5px 0;

            color: #666;
        }

        .report-date {
            margin-top: 10px;

            font-size: 12px;

            color: #555;
        }

        .section {
            margin-bottom: 25px;

            page-break-inside: avoid;
        }

        .section-title {
            background: #146c43;

            color: #ffffff;

            padding: 10px 12px;

            font-size: 15px;

            font-weight: bold;

            margin-bottom: 12px;
        }

        .summary-grid {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 12px;
        }

        .summary-box {
            border: 1px solid #ddd;

            padding: 15px;

            text-align: center;

            min-height: 80px;
        }

        .summary-value {
            font-size: 22px;

            font-weight: bold;

            color: #146c43;
        }

        .summary-label {
            margin-top: 5px;

            color: #666;
        }

        table {
            width: 100%;

            border-collapse: collapse;

            margin-top: 5px;
        }

        th,
        td {
            border: 1px solid #ccc;

            padding: 8px;

            text-align: left;

            vertical-align: middle;
        }

        th {
            background: #f1f5f3;

            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .risk-high {
            font-weight: bold;

            color: #b42318;
        }

        .risk-medium {
            font-weight: bold;

            color: #946200;
        }

        .risk-low {
            font-weight: bold;

            color: #146c43;
        }

        .condition {
            font-weight: bold;
        }

        .condition-critical {
            color: #b42318;
        }

        .condition-poor {
            color: #9a5a00;
        }

        .condition-fair {
            color: #946200;
        }

        .condition-good {
            color: #2f7d32;
        }

        .condition-excellent {
            color: #146c43;
        }

        .total-row {
            font-weight: bold;

            background: #f1f5f3;
        }

        .methodology {
            border: 1px solid #ddd;

            padding: 15px;

            line-height: 1.7;
        }

        .print-button {
            position: fixed;

            top: 20px;

            right: 20px;

            padding: 10px 18px;

            background: #198754;

            color: #ffffff;

            border: none;

            border-radius: 5px;

            cursor: pointer;

            font-size: 14px;

            z-index: 9999;
        }

        .print-button:hover {
            background: #146c43;
        }

        .footer {
            margin-top: 35px;

            padding-top: 12px;

            border-top: 1px solid #ccc;

            text-align: center;

            font-size: 11px;

            color: #777;
        }

        @media print {

            body {
                padding: 10mm;
            }

            .print-button {
                display: none !important;
            }

            .section {
                page-break-inside: avoid;
            }

            .header {
                page-break-after: avoid;
            }

        }

        @media (max-width: 700px) {

            body {
                padding: 15px;
            }

            .summary-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

        }

    </style>

</head>


<body>


    {{-- PRINT BUTTON --}}

    <button
        type="button"
        class="print-button"
        onclick="window.print()"
    >
        🖨 Print Report
    </button>


    <div class="report-container">


        {{-- HEADER --}}

        <div class="header">

            <h1>
                SOLAR-BATTERY MAINTENANCE SYSTEM
            </h1>

            <h2>
                Preventive Maintenance & Decision-Support Report
            </h2>


            @if($installationId)

                @php

                    $selectedInstallation =
                        $installations->firstWhere(
                            'id',
                            $installationId
                        );

                @endphp

                <p>

                    Installation:

                    <strong>
                        {{ $selectedInstallation->name ?? 'Selected Installation' }}
                    </strong>

                </p>

            @else

                <p>
                    System-Wide Report
                </p>

            @endif


            <div class="report-date">

                Generated on
                {{ now()->format('d F Y, h:i A') }}

            </div>

        </div>


        {{-- SYSTEM SUMMARY --}}

        <div class="section">

            <div class="section-title">
                1. System Summary
            </div>


            <div class="summary-grid">


                <div class="summary-box">

                    <div class="summary-value">
                        {{ $statistics['total_components'] }}
                    </div>

                    <div class="summary-label">
                        Components
                    </div>

                </div>


                <div class="summary-box">

                    <div class="summary-value">
                        {{ $statistics['total_inspections'] }}
                    </div>

                    <div class="summary-label">
                        Inspections
                    </div>

                </div>


                <div class="summary-box">

                    <div class="summary-value">
                        {{ $statistics['total_maintenance'] }}
                    </div>

                    <div class="summary-label">
                        Maintenance Schedules
                    </div>

                </div>


                <div class="summary-box">

                    <div class="summary-value">

                        ₦{{ number_format(
                            $statistics['total_cost'],
                            2
                        ) }}

                    </div>

                    <div class="summary-label">
                        Recorded Cost
                    </div>

                </div>


            </div>

        </div>


        {{-- COMPONENT CONDITION --}}

        <div class="section">

            <div class="section-title">
                2. Component Condition Summary
            </div>


            <table>

                <thead>

                    <tr>

                        <th>
                            Condition
                        </th>

                        <th class="text-center">
                            Number of Components
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach($conditionData as $condition => $count)

                        <tr>

                            <td>

                                <span
                                    class="condition condition-{{ strtolower($condition) }}"
                                >
                                    {{ $condition }}
                                </span>

                            </td>

                            <td class="text-center">
                                {{ $count }}
                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- MAINTENANCE STATUS --}}

        <div class="section">

            <div class="section-title">
                3. Maintenance Status
            </div>


            <table>

                <thead>

                    <tr>

                        <th>
                            Status
                        </th>

                        <th class="text-center">
                            Number
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td>
                            Scheduled
                        </td>

                        <td class="text-center">
                            {{ $scheduledMaintenance }}
                        </td>

                    </tr>


                    <tr>

                        <td>
                            Due Soon
                        </td>

                        <td class="text-center">
                            {{ $dueMaintenance }}
                        </td>

                    </tr>


                    <tr>

                        <td>
                            Overdue
                        </td>

                        <td class="text-center">
                            {{ $overdueMaintenance }}
                        </td>

                    </tr>


                    <tr>

                        <td>
                            Completed
                        </td>

                        <td class="text-center">
                            {{ $completedMaintenance }}
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        {{-- COST SUMMARY --}}

        <div class="section">

            <div class="section-title">
                4. Maintenance Cost Summary
            </div>


            <table>

                <thead>

                    <tr>

                        <th>
                            Cost Category
                        </th>

                        <th class="text-right">
                            Amount
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <tr>

                        <td>
                            Maintenance
                        </td>

                        <td class="text-right">

                            ₦{{ number_format(
                                $maintenanceCost,
                                2
                            ) }}

                        </td>

                    </tr>


                    <tr>

                        <td>
                            Repair
                        </td>

                        <td class="text-right">

                            ₦{{ number_format(
                                $repairCost,
                                2
                            ) }}

                        </td>

                    </tr>


                    <tr>

                        <td>
                            Replacement
                        </td>

                        <td class="text-right">

                            ₦{{ number_format(
                                $replacementCost,
                                2
                            ) }}

                        </td>

                    </tr>


                    <tr>

                        <td>
                            Parts / Materials
                        </td>

                        <td class="text-right">

                            ₦{{ number_format(
                                $partsCost,
                                2
                            ) }}

                        </td>

                    </tr>


                    <tr>

                        <td>
                            Labour / Service
                        </td>

                        <td class="text-right">

                            ₦{{ number_format(
                                $labourCost,
                                2
                            ) }}

                        </td>

                    </tr>


                    <tr>

                        <td>
                            Other
                        </td>

                        <td class="text-right">

                            ₦{{ number_format(
                                $otherCost,
                                2
                            ) }}

                        </td>

                    </tr>


                    <tr class="total-row">

                        <td>
                            Total Recorded Cost
                        </td>

                        <td class="text-right">

                            ₦{{ number_format(
                                $totalCost,
                                2
                            ) }}

                        </td>

                    </tr>


                </tbody>

            </table>

        </div>


        {{-- REPLACEMENT FORECAST --}}

        <div class="section">

            <div class="section-title">
                5. Replacement Forecast
            </div>


            <table>

                <thead>

                    <tr>

                        <th>
                            Component
                        </th>

                        <th>
                            Condition
                        </th>

                        <th>
                            Degradation
                        </th>

                        <th>
                            Remaining Life
                        </th>

                        <th>
                            Risk
                        </th>

                        <th>
                            Recommended Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                    @forelse(
                        $replacementForecasts
                        as $forecast
                    )


                        <tr>

                            <td>
                                {{ $forecast->component->name ?? 'N/A' }}
                            </td>


                            <td>
                                {{ $forecast->current_condition ?? 'N/A' }}
                            </td>


                            <td>

                                {{ $forecast->degradation_rate !== null

                                    ? number_format(
                                        $forecast->degradation_rate,
                                        2
                                    ) . '%'

                                    : 'N/A'
                                }}

                            </td>


                            <td>

                                {{ $forecast->estimated_remaining_life !== null

                                    ? number_format(
                                        $forecast->estimated_remaining_life,
                                        1
                                    ) . ' years'

                                    : 'N/A'
                                }}

                            </td>


                            <td>

                                @if(
                                    $forecast->risk_level === 'High'
                                )

                                    <span class="risk-high">
                                        High
                                    </span>

                                @elseif(
                                    $forecast->risk_level === 'Medium'
                                )

                                    <span class="risk-medium">
                                        Medium
                                    </span>

                                @else

                                    <span class="risk-low">
                                        Low
                                    </span>

                                @endif

                            </td>


                            <td>
                                {{ $forecast->recommended_action }}
                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center"
                            >
                                No replacement forecasts available.
                            </td>

                        </tr>

                    @endforelse


                </tbody>

            </table>

        </div>


        {{-- RECENT INSPECTIONS --}}

        <div class="section">

            <div class="section-title">
                6. Recent Inspection Records
            </div>


            <table>

                <thead>

                    <tr>

                        <th>
                            Date
                        </th>

                        <th>
                            Installation
                        </th>

                        <th>
                            Inspector
                        </th>

                        <th>
                            Overall Condition
                        </th>

                        <th>
                            Next Inspection
                        </th>

                    </tr>

                </thead>


                <tbody>


                    @forelse(
                        $recentInspections
                        as $inspection
                    )


                        <tr>

                            <td>

                                {{
                                    \Carbon\Carbon::parse(
                                        $inspection->inspection_date
                                    )->format('d M Y')
                                }}

                            </td>


                            <td>
                                {{ $inspection->installation->name ?? 'N/A' }}
                            </td>


                            <td>
                                {{ $inspection->inspector->name ?? 'N/A' }}
                            </td>


                            <td>
                                {{ $inspection->overall_condition ?? 'N/A' }}
                            </td>


                            <td>

                                @if(
                                    $inspection->next_inspection_date
                                )

                                    {{
                                        \Carbon\Carbon::parse(
                                            $inspection->next_inspection_date
                                        )->format('d M Y')
                                    }}

                                @else

                                    Not scheduled

                                @endif

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center"
                            >
                                No inspection records available.
                            </td>

                        </tr>

                    @endforelse


                </tbody>

            </table>

        </div>


        {{-- INSTALLED COMPONENTS --}}

        <div class="section">

            <div class="section-title">
                7. Installed Components
            </div>


            <table>

                <thead>

                    <tr>

                        <th>
                            Component
                        </th>

                        <th>
                            Type
                        </th>

                        <th>
                            Manufacturer
                        </th>

                        <th>
                            Model
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>


                    @forelse(
                        $components
                        as $component
                    )


                        <tr>

                            <td>
                                {{ $component->name }}
                            </td>

                            <td>
                                {{ $component->componentType->name ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $component->manufacturer ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $component->model ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $component->status ?? 'N/A' }}
                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center"
                            >
                                No components available.
                            </td>

                        </tr>

                    @endforelse


                </tbody>

            </table>

        </div>


        {{-- REPORT INTERPRETATION --}}

        <div class="section">

            <div class="section-title">
                8. Report Interpretation
            </div>


            <div class="methodology">

                This report consolidates information recorded in the
                Solar-Battery Preventive Maintenance and Replacement
                Decision-Support System.

                <br>
                <br>

                Degradation values represent performance loss
                relative to available reference measurements.

                <br>
                <br>

                Replacement risk is a planning indicator based on
                recorded component condition, degradation, age,
                maintenance history and expected lifespan.

                It should not be interpreted as an exact component
                failure date.

                <br>
                <br>

                Recorded costs represent maintenance, repair,
                replacement, parts/materials, labour/service and
                other expenditure entered into the system.

            </div>

        </div>


        {{-- FOOTER --}}

        <div class="footer">

            Solar-Battery Preventive Maintenance and
            Replacement Decision-Support System

            <br>

            Generated by the system for maintenance planning
            and reporting.

        </div>


    </div>


</body>

</html>