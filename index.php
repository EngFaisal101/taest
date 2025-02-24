<?php

// التحقق من وجود ملف صورة تم رفعه
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];

        // مسار الصورة المؤقت على الخادم
        $imagePath = $image['tmp_name'];

        // العنوان API الخاص بموقع Postimages
        $url = 'https://postimages.org/json';

        // تهيئة cURL
        $ch = curl_init();

        // بيانات POST لرفع الصورة
        $data = [
            'file' => new CURLFile($imagePath) // رفع الملف
        ];

        // تهيئة الاتصال cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // تنفيذ الطلب
        $response = curl_exec($ch);

        // التحقق من حدوث خطأ في الاتصال
        if ($response === false) {
            echo "خطأ في الاتصال: " . curl_error($ch);
        } else {
            // تحويل الاستجابة من JSON
            $responseData = json_decode($response, true);

            // التحقق من وجود الرابط في الاستجابة
            if (isset($responseData['url'])) {
                echo "تم رفع الصورة بنجاح!<br>";
                echo "رابط الصورة: <a href='" . $responseData['url'] . "' target='_blank'>" . $responseData['url'] . "</a>";
            } else {
                echo "حدث خطأ في رفع الصورة. ربما لم يكن الملف صالحًا.";
            }
        }

        // إغلاق الاتصال
        curl_close($ch);
    } else {
        // عرض رسالة خطأ مفصلة في حال وجود مشكلة في رفع الملف
        if (isset($_FILES['image'])) {
            switch ($_FILES['image']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    echo "الملف أكبر من الحجم المسموح به في إعدادات الخادم.";
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    echo "الملف أكبر من الحجم المسموح به في النموذج.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    echo "تم رفع جزء من الملف فقط.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    echo "لم يتم اختيار أي ملف.";
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    echo "لا يوجد مجلد مؤقت لرفع الملف.";
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    echo "حدث خطأ في الكتابة إلى القرص.";
                    break;
                case UPLOAD_ERR_EXTENSION:
                    echo "تم إيقاف رفع الملف بواسطة إضافة PHP.";
                    break;
                default:
                    echo "حدث خطأ غير معروف أثناء رفع الملف.";
            }
        } else {
            echo "لم يتم اختيار أي صورة لرفعها.";
        }
    }
} else {
    echo "الطريقة غير صحيحة. تأكد من إرسال البيانات عبر POST.";
}

?>


<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رفع صورة</title>
</head>
<body>
    <h2>رفع صورة إلى Postimages</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="image">اختر صورة لرفعها:</label>
        <input type="file" name="image" id="image" required><br><br>
        <input type="submit" value="رفع الصورة">
    </form>
</body>
</html>
