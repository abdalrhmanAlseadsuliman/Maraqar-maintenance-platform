<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير طلبات الصيانة</title>
    <style>
        @font-face {
            font-family: 'Amiri';
            font-style: normal;
            font-weight: 400;
            src: url('{{ public_path('fonts/Amiri-Regular.ttf') }}') format('truetype');
        }

        body {
            font-family: 'Amiri', sans-serif;
            direction: rtl;
            text-align: right;
            margin-bottom: 150px; /* لتوفير مساحة للفوتر */
            padding: 20px;
            background-color: #fff;
        }

        .header-img, .footer-img {
            width: 100%;
            height: auto;
        }

        .footer-img {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
        }

        .record {
            border: 1px solid #ccc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .record h3 {
            margin-top: 0;
            color: #2c3e50;
        }

        .record p {
            margin: 5px 0;
        }

        h2.title {
            text-align: center;
            margin: 20px 0;
            color: #000;
        }
    </style>
</head>
<body>

    {{-- صورة الهيدر --}}
    <img src="{{ public_path('mar/header.jpg') }}" class="header-img">

    {{-- العنوان --}}
    <h2 class="title">تقرير طلبات الصيانة</h2>

    {{-- قائمة الطلبات --}}
    @foreach ($records as $index => $record)
        <div class="record">
            <h3>طلب رقم {{ $index + 1 }}</h3>
            <p><strong>المعرف:</strong> {{ $record->id }}</p>
            <p><strong>نوع الطلب:</strong> {{ $record->request_type }}</p>
            <p><strong>الحالة:</strong> {{ $record->status }}</p>
            <p><strong>تاريخ الإرسال:</strong> {{ $record->submitted_at }}</p>
            <p><strong>عدد زيارات الفني:</strong> {{ $record->technician_visits }}</p>
            <p><strong>اسم الفني:</strong> {{ $record->technician_name }}</p>
            <p><strong>التكلفة:</strong> ${{ number_format($record->cost, 2) }}</p>
            <p><strong>الوصف:</strong> {{ $record->problem_description }}</p>
            <p><strong>اسم مالك العقار:</strong> {{ optional($record->property->owner)->name }}</p>
        </div>
    @endforeach

    {{-- صورة الفوتر --}}
    <footer class="footer-img">
        <img src="{{ public_path('mar/footer.jpg') }}">
    </footer>

</body>
</html>
