    <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PregisterSchoolsController;


Route::put('/', function(){
    return response()->json(['message' => 'selamat datang di halaman utama kami']);
});

require __DIR__ . '/api/auth/auth.php';
require __DIR__ . '/api/auth/users.php';
require __DIR__ . '/api/comment.php';
require __DIR__ . '/api/logs.php';
require __DIR__ . '/api/major.php';
require __DIR__ . '/api/privasi/personal-data.php';
require __DIR__ . '/api/privasi/experience.php';
require __DIR__ . '/api/privasi/education.php';
require __DIR__ . '/api/privasi/pregister.php';
//require __DIR__ . '/api/privasi/student.php';
require __DIR__ . '/api/location/location.php';
