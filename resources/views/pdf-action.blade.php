<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
        }

        .container {
            width: 100%;
            border-collapse: collapse;
        }

        .container, .container th, .container td {
            border: 1px solid black;
            padding: 10px;
        }

        .container th {
            background-color: #f0f0f0;
        }

        .image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">تقرير طلبات الصيانة</h2>

    <table class="container">
        <thead>
            <tr>
                <th>المعرف</th>
                <th>نوع الطلب</th>
                <th>الحالة</th>
                <th>تاريخ الإرسال</th>
                <th>عدد زيارات الفني</th>
                <th>اسم الفني</th>
                <th>التكلفة</th>
                <th>الوصف</th>
                <th>الصورة</th>
            </tr>
        </thead>
        <tbody>
                <tr>
                    <td>{{ $record->id }}</td>
                    <td>{{ $record->request_type }}</td>
                    <td>{{ $record->status }}</td>
                    <td>{{ $record->submitted_at }}</td>
                    <td>{{ $record->technician_visits }}</td>
                    <td>{{ $record->technician_name }}</td>
                    <td>${{ number_format($record->cost, 2) }}</td>
                    <td>{{ $record->problem_description }}</td>
                    <td>
                        @foreach ($record->images as $image)
                            <img src="{{ public_path('storage/' . $image->image_path) }}" class="image">
                        @endforeach
                    </td>
                </tr>

        </tbody>
    </table>

</body>
</html>
