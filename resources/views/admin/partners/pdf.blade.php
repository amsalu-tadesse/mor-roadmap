<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Assigned Activities - {{ $partner->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333333;
            margin: 10px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #0056b3;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0 0 0;
            color: #555555;
            font-size: 12px;
        }
        .meta-info {
            margin-bottom: 20px;
        }
        .meta-info table {
            width: 100%;
            border: none;
        }
        .meta-info td {
            padding: 2px 0;
            border: none;
        }
        .activities-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .activities-table th, .activities-table td {
            border: 1px solid #dddddd;
            padding: 6px 5px;
            text-align: left;
            vertical-align: top;
        }
        .activities-table th {
            background-color: #f2f2f2;
            color: #333333;
            font-weight: bold;
        }
        .activities-table tr:nth-child(even) {
            background-color: #fafafa;
        }
        .badge {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
        }
        .bg-low { background-color: #17a2b8; }
        .bg-medium { background-color: #ffc107; color: #333333; }
        .bg-high { background-color: #dc3545; }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #888888;
            border-top: 1px solid #eeeeee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Reform Initiatives Roadmap</h2>
        <p>Assigned Activities Report</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 15%; font-weight: bold;">Partner:</td>
                <td>{{ $partner->name }}</td>
                <td style="width: 15%; font-weight: bold; text-align: right;">Date Generated:</td>
                <td style="width: 25%; text-align: right;">{{ now()->format('Y-m-d H:i') }}</td>
            </tr>
            @if($partner->email)
            <tr>
                <td style="font-weight: bold;">Email:</td>
                <td colspan="3">{{ $partner->email }}</td>
            </tr>
            @endif
        </table>
    </div>

    <table class="activities-table">
        <thead>
            <tr>
                <th style="width: 3%; text-align: center;">No</th>
                <th style="width: 20%;">Initiative</th>
                <th style="width: 32%;">Activity</th>
                <th style="width: 15%;">Directorates</th>
                <th style="width: 6%; text-align: center;">Priority</th>
                <th style="width: 12%; text-align: center;">Timeline</th>
                <th style="width: 6%; text-align: right;">Budget</th>
                <th style="width: 6%; text-align: center;">Progress</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $index => $activity)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $activity->initiative->name ?? 'N/A' }}</td>
                    <td>{{ $activity->activities }}</td>
                    <td>{{ $activity->directorates->pluck('name')->join(', ') ?: 'N/A' }}</td>
                    <td style="text-align: center;">
                        @php
                            $priorityCode = $activity->priority;
                            $priorityName = \App\Models\Activity::PRIORITIES[$priorityCode] ?? 'N/A';
                            $priorityClass = 'bg-low';
                            if ($priorityCode === 'M') $priorityClass = 'bg-medium';
                            if ($priorityCode === 'H') $priorityClass = 'bg-high';
                        @endphp
                        <span class="badge {{ $priorityClass }}">{{ $priorityName }}</span>
                    </td>
                    <td style="text-align: center; font-size: 10px;">
                        {{ $activity->start_date ? $activity->start_date->format('Y-m-d') : 'N/A' }}<br>to<br>
                        {{ $activity->end_date ? $activity->end_date->format('Y-m-d') : 'N/A' }}
                    </td>
                   <td style="text-align: right;">
    {{ $activity->budget  }}
</td>

                    <td style="text-align: center;">
                        <strong>{{ $activity->completion ?? 0 }}%</strong>
                        <div style="font-size: 8px; color: #666666; margin-top: 2px;">
                            {{ $activity->activityStatus->name ?? 'N/A' }}
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 15px; font-weight: bold; color: #666666;">
                        No assigned activities found for this partner.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Ministry of Revenues - Reform Initiatives Roadmap System &copy; {{ date('Y') }}
    </div>

</body>
</html>
