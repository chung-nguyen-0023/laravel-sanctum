# Laravel Sanctum là gì
Laravel Sanctum lấy cảm hứng từ `Github personal access token` cung cấp 1 hệ thống xác thực cho `SPAs` (Single Page Applications), mobile applications thông qua các api dự trên token. Sanctum cho phép người dùng tạo ra nhiều `token` cho tài khoản của họ. Những token này. Những `token` này được cấp các khả năng, phạm vi để thực hiện 1 số hành động xác định.

# Cài đặt
Để cài đặt Laravel Sanctum thông qua Composer
```
composer require laravel/sanctum
```

Tiếp theo, chúng ta sẽ publish file config và migration sử dụng câu lệnh `vendor:publish`:
```
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

Cuối cùng bạn phải chạy câu lệnh migrate để tạo bảng để lưu `API tokens`
```
php artisan migrate
```

Nếu bạn định sử dụng Sanctum để xác thực cho 1 ứng dụng SPA, bạn cần thêm `Sanctum's middleware` trong `api` middleware bên trong file `app/Http/Kernel.php`
```php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

# Migration Customization
Nếu bạn không muốn sử dụng file migration mặc định của Sanctum, bạn cần sử dụng phương thức `Sanctum::ignoreMigrations` trong phương thức `register` bên trong lớp `App\Providers\AppServiceProvider`. Bạn có thể xuất các di chuyển mặc định bằng cách thực hiện lệnh sau:
```
php artisan vendor:publish --tag=sanctum-migrations
```

# Tạo dữ liệu mẫu (Seeder)
Để chuẩn bị dữ liệu cho ứng dụng, ta cần tạo 1 thêm bảng Category và dữ liệu mẫu cho bảng đó. Câu lệnh tạo migration cho bảng Category
```
php artisan make:migration create_categories_table
```

Sau khi chạy xong câu lệnh trên, sẽ có 1 file `create_categories_tables` được tạo ra bên trong thư mục `database/migrations`. Chúng ta cần chỉnh sửa 1 chút ở file đó
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
```

Tiếp theo chúng ta sẽ tạo `Model` cho bảng `Category` bằng lệnh
```
php artisan make:model Category
```

Câu lệnh bên trên sẽ tạo 1 file `Category.php` trong thư mục `app/Models`, và chúng ta cũng cần chỉnh sửa 1 chút cho file này
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    const STATUS_ACTIVE  = 1;
    const STATUS_PENDING = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'order',
        'status',
    ];
}
```

Chúng ta sẽ tạo dữ liệu mẫu cho bảng `Category` bằng cách tạo ra file `CategoriesSeeder` thông qua câu lệnh
```
php artisan make:seeder CategoriesSeeder
```

Câu lệnh trên tạo cho chúng ta 1 file `CategoriesSeeder.php` bên trong thư mục `database/seeders`. Chúng ta cần phải chỉnh sửa 1 chút cho file này
```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'Laptop',
                'slug' => 'laptop',
            ],
            [
                'name' => 'PC',
                'slug' => 'pc',
            ],
            [
                'name' => 'Tablet',
                'slug' => 'tablet',
            ],
        ];

        foreach ($categories as $item) {
            $category = new Category;
            $category = Category::updateOrCreate($item,
                $item
            );
            $category->status = Category::STATUS_ACTIVE;
            $category->save();
        }
    }
}
```

Sau đó, chúng ta sẽ gọi file `CategoriesTableSeeder` ở bên trong file `database/seeders/DatabaseSeeder.php`.
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        $this->call(CategoriesTableSeeder::class);
    }
}
```

Ta chạy câu lệnh `php artisan db:seed` để tạo dữ liệu mẫu cho bảng `User` và bảng `Category`

# Tạo API Tokens
