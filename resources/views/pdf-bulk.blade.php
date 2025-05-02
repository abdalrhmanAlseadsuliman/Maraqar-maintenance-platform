<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير طلبات الصيانة</title>
    <style>
        @font-face {
            font-family: 'Amiri';
            src: url('{{ public_path('fonts/Amiri-Regular.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Amiri', sans-serif;
            direction: rtl;
            text-align: right;
        }

        .record {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .record h3 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .record p {
            margin: 4px 0;
        }

        hr {
            margin: 20px 0;
            border: none;
            border-top: 1px dashed #aaa;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">قائمة طلبات الصيانة</h2>

    @foreach ($records as $index => $record)
        <div class="record">
            <h3>طلب رقم {{ $record->id }} ({{ $index + 1 }})</h3>
            <p><strong>اسم مالك العقار:</strong> {{ $record->property->owner->name ?? 'غير متوفر' }}</p>
            <p><strong>نوع الطلب:</strong> {{ $record->request_type }}</p>
            <p><strong>الحالة:</strong> {{ $record->status }}</p>
            <p><strong>تاريخ الإرسال:</strong> {{ $record->submitted_at }}</p>
            <p><strong>عدد زيارات الفني:</strong> {{ $record->technician_visits }}</p>
            <p><strong>اسم الفني:</strong> {{ $record->technician_name }}</p>
            <p><strong>التكلفة:</strong> ${{ number_format($record->cost, 2) }}</p>
            <p><strong>الوصف:</strong> {{ $record->problem_description }}</p>
        </div>
        <hr>
    @endforeach

</body>
</html>
