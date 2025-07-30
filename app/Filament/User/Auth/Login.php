<?php

// namespace App\Filament\User\Auth;

// use Filament\Forms\Form;
// use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\Checkbox;
// use Filament\Forms\Components\ViewField;
// use Filament\Pages\Auth\Login as BaseLogin;
// use Illuminate\Validation\ValidationException;

// class Login extends BaseLogin
// {
//     // إعدادات العناوين والزر
//     protected static ?string $title = 'تسجيل الدخول';
//     protected ?string $heading = 'تسجيل الدخول';
//     protected ?string $subheading = 'مرحباً بك، الرجاء تسجيل الدخول إلى حسابك';
//     protected ?string $submitButtonLabel = 'دخول';

//     // تحديد القالب المستخدم
//     protected static string $view = 'filament.pages.auth.login';

//     // بناء نموذج تسجيل الدخول
//     public function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 $this->getLoginFormComponent(),      // مكون إدخال البريد أو الهاتف
//                 $this->getPasswordFormComponent(),   // مكون إدخال كلمة المرور
//                 $this->getRememberFormComponent(),   // مكون "تذكرني"
//             ])
//             ->statePath('data');
//     }

//     // مكون إدخال البريد أو رقم الهاتف
//     protected function getLoginFormComponent(): TextInput
//     {
//         return TextInput::make('login')
//             ->label('البريد الإلكتروني أو رقم الهاتف') // تعديل العنوان ليغطي الحالتين
//             ->placeholder('أدخل بريدك الإلكتروني أو رقم هاتفك')
//             ->required()
//             ->autofocus()
//             ->autocomplete('username') // يستخدمه المتصفح لتعبئة الحقل
//             ->extraInputAttributes(['tabindex' => 1]);
//     }

//     // مكون إدخال كلمة المرور
//     protected function getPasswordFormComponent(): TextInput
//     {
//         return TextInput::make('password')
//             ->label('كلمة المرور')
//             ->placeholder('أدخل كلمة المرور')
//             ->helperText('🔒 في حال لم تقم بتغييرها، فإن كلمة المرور هي رقم البطاقة.')
//             ->password()
//             ->revealable()
//             ->required()
//             ->autocomplete('current-password')
//             ->extraInputAttributes(['tabindex' => 2]);
//     }


//     // مكون خيار "تذكرني"
//     protected function getRememberFormComponent(): Checkbox
//     {
//         return Checkbox::make('remember')
//             ->label('تذكرني');
//     }

//     // طريقة استخراج بيانات تسجيل الدخول
//     protected function getCredentialsFromFormData(array $data): array
//     {
//         // تحديد إذا كانت القيمة بريد إلكتروني أو رقم هاتف
//         if (filter_var($data['login'], FILTER_VALIDATE_EMAIL)) {
//             // إذا كانت القيمة بريد إلكتروني
//             $loginField = 'email';
//         } else {
//             // إذا كانت القيمة رقم هاتف (ملاحظة: يمكنك إضافة تحقق إضافي هنا لو أردت)
//             $loginField = 'phone';
//         }

//         // بناء بيانات تسجيل الدخول
//         return [
//             $loginField => $data['login'],
//             'password' => $data['password'],
//         ];
//     }

//     // تخصيص رسالة الخطأ عند فشل تسجيل الدخول
//     protected function throwFailureValidationException(): never
//     {
//         throw ValidationException::withMessages([
//             'data.login' => 'البريد الإلكتروني أو رقم الهاتف أو كلمة المرور غير صحيحة.',
//         ]);
//     }
// }




// namespace App\Filament\User\Auth;

// use Filament\Forms\Form;
// use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\Checkbox;
// use Filament\Pages\Auth\Login as BaseLogin;
// use Illuminate\Validation\ValidationException;

// class Login extends BaseLogin
// {
//     // إعدادات العناوين والزر
//     protected static ?string $title = 'تسجيل الدخول';
//     protected ?string $heading = 'تسجيل الدخول';
//     protected ?string $subheading = 'مرحباً بك، الرجاء إدخال رقم البطاقة الشخصية';
//     protected ?string $submitButtonLabel = 'دخول';

//     // تحديد القالب المستخدم
//     protected static string $view = 'filament.pages.auth.login';

//     // بناء نموذج تسجيل الدخول
//     public function form(Form $form): Form
//     {
//         return $form
//             ->schema([
//                 $this->getCardIdFormComponent(),     // مكون إدخال رقم البطاقة الشخصية
//                 $this->getRememberFormComponent(),   // مكون "تذكرني"
//             ])
//             ->statePath('data');
//     }

//     // مكون إدخال رقم البطاقة الشخصية
//     protected function getCardIdFormComponent(): TextInput
//     {
//         return TextInput::make('national_id')
//             ->label('رقم البطاقة الشخصية')
//             ->placeholder('أدخل رقم البطاقة الشخصية')
//             ->required()
//             ->autofocus()
//             ->autocomplete('username')
//             ->numeric() // للتأكد من أن المدخل أرقام فقط
//             ->minLength(9) // تحديد الحد الأدنى لطول الرقم (قم بتعديله حسب نظام بلدك)
//             ->maxLength(15) // تحديد الحد الأقصى لطول الرقم (قم بتعديله حسب نظام بلدك)
//             ->helperText('🆔 رقم البطاقة الشخصية الخاص بك')
//             ->extraInputAttributes(['tabindex' => 1]);
//     }

//     // مكون خيار "تذكرني"
//     protected function getRememberFormComponent(): Checkbox
//     {
//         return Checkbox::make('remember')
//             ->label('تذكرني');
//     }

//     // طريقة استخراج بيانات تسجيل الدخول
//     protected function getCredentialsFromFormData(array $data): array
//     {
//         // استخدام رقم البطاقة الشخصية لتسجيل الدخول
//         return [
//             'national_id' => $data['national_id'],
//             'password' => $data['national_id'], // استخدام رقم البطاقة كـ password أيضاً
//         ];
//     }

//     // تخصيص رسالة الخطأ عند فشل تسجيل الدخول
//     protected function throwFailureValidationException(): never
//     {
//         throw ValidationException::withMessages([
//             'data.national_id' => 'رقم البطاقة الشخصية غير صحيح أو غير مسجل في النظام.',
//         ]);
//     }
// }





namespace App\Filament\User\Auth;

use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class Login extends BaseLogin
{
    // تحديد Guard المستخدم للمستخدمين العاديين
    protected static string $guard = 'users';

    // إعدادات العناوين والزر
    protected static ?string $title = 'تسجيل الدخول';
    protected ?string $heading = 'تسجيل الدخول';
    protected ?string $subheading = 'مرحباً بك، الرجاء إدخال رقم البطاقة الشخصية';
    protected ?string $submitButtonLabel = 'دخول';

    // تحديد القالب المستخدم
    protected static string $view = 'filament.pages.auth.login';

    // بناء نموذج تسجيل الدخول
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getCardIdFormComponent(),     // مكون إدخال رقم البطاقة الشخصية
                $this->getRememberFormComponent(),   // مكون "تذكرني"
            ])
            ->statePath('data');
    }

    // مكون إدخال رقم البطاقة الشخصية
    protected function getCardIdFormComponent(): TextInput
    {
        return TextInput::make('national_id')
            ->label('رقم البطاقة الشخصية')
            ->placeholder('أدخل رقم البطاقة الشخصية')
            ->required()
            ->autofocus()
            ->autocomplete('username')
            ->numeric() // للتأكد من أن المدخل أرقام فقط
            ->minLength(9) // تحديد الحد الأدنى لطول الرقم (قم بتعديله حسب نظام بلدك)
            ->maxLength(15) // تحديد الحد الأقصى لطول الرقم (قم بتعديله حسب نظام بلدك)
            ->helperText('🆔 رقم البطاقة الشخصية الخاص بك')
            ->extraInputAttributes(['tabindex' => 1]);
    }

    // مكون خيار "تذكرني"
    protected function getRememberFormComponent(): Checkbox
    {
        return Checkbox::make('remember')
            ->label('تذكرني');
    }

    // طريقة استخراج بيانات تسجيل الدخول
    protected function getCredentialsFromFormData(array $data): array
    {
        // استخدام رقم البطاقة الشخصية لتسجيل الدخول
        return [
            'national_id' => $data['national_id'],
            'password' => $data['national_id'], // استخدام رقم البطاقة كـ password أيضاً
        ];
    }

    // تخصيص رسالة الخطأ عند فشل تسجيل الدخول
    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.card_id' => 'رقم البطاقة الشخصية غير صحيح أو غير مسجل في النظام.',
        ]);
    }
}
