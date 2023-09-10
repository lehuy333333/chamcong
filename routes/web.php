<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\timesheet\CalendarController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\timesheet\WorkSymbolController;
use App\Http\Controllers\workdate\WorkDateController;
use App\Http\Controllers\Level\LevelController;

use App\Http\Controllers\Position\PositionController;

use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeTypeController;
use App\Http\Controllers\Report\ReportTimesheet;
use App\Http\Controllers\Task\taskController;
use App\Http\Controllers\Attendance\AttendanceController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//authorization
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/checklogin', [AuthController::class, 'login'])->name('checklogin');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/logout', [AuthController::class, 'signOut']);


    Route::get('/test', [AttendanceController::class, 'index']);
    //Home
    Route::get('home/index', [AuthController::class, 'home'])->name('home.index');


    //task
    Route::get('tasks', [taskController::class, 'index']);
    Route::get('task/delete', [taskController::class, 'deleteTask']);
    Route::get('task/{selectDate}', [taskController::class, 'tasksByDay']);
    Route::post('task/add', [taskController::class, 'addTask']);
    Route::post('task/update', [taskController::class, 'updateTask']);

    Route::get('task/file-import', [taskController::class, 'importView'])->name('import-view');
    Route::post('task/import', [taskController::class, 'import'])->name('import');

    //Calendar Tong Hop
    Route::get('timesheet/Calendar', [CalendarController::class, 'index'])->name('timesheet.index');

    Route::get('timesheet/getSurplusMonth', [CalendarController::class, 'getMonthSurplus']);

    Route::get('timesheet/{selectDate}', [CalendarController::class, 'timesheet']);

    Route::post('timesheet/timekeeping', [CalendarController::class, 'timekeeping']);
    Route::post('timesheet/setSurplusMonth', [CalendarController::class, 'setSurplusMonth']);

    //***Management Phòng Ban***
    //Tạo Phòng Ban
    Route::post('department/add', [DepartmentController::class, 'addDepartment']);
    Route::post('department/update', [DepartmentController::class, 'updateDepartment']);
    Route::get('department/delete/{id}', [DepartmentController::class, 'deleteDepartment']);
    Route::get('department/index', [DepartmentController::class, 'getDepartment'])->name('department.index');
    //Route::get('/to/phongban_json', [PhongbanController::class, 'jsonPhongbanById']);

    //***Management Level***
    Route::post('level/create', [LevelController::class, 'addLevel']);
    Route::post('level/update', [LevelController::class, 'updateLevel']);
    Route::get('level/delete/{id}', [LevelController::class, 'deleteLevel']);
    Route::get('level/index', [LevelController::class, 'index'])->name('level.index');

    //***Management position***
    Route::post('position/create', [PositionController::class, 'addPosition']);
    Route::post('position/update', [PositionController::class, 'updatePosition']);
    Route::get('position/delete/{id}', [PositionController::class, 'deletePosition']);
    Route::get('position/index', [PositionController::class, 'index'])->name('position.index');

    //***Management Employee_Type***
    Route::post('Etype/create', [EmployeeTypeController::class, 'addEmployee_type']);
    Route::post('Etype/update', [EmployeeTypeController::class, 'updateEmployee_type']);
    Route::get('Etype/delete/{id}', [EmployeeTypeController::class, 'deleteEmployee_type']);
    Route::get('Etype/index', [EmployeeTypeController::class, 'index'])->name('Etype.index');


    //***Management Symbol***
    Route::post('symbol/create', [WorkSymbolController::class, 'addSymbol']);
    Route::post('symbol/update', [WorkSymbolController::class, 'updatesymbol']);
    Route::get('symbol/delete/{id}', [WorkSymbolController::class, 'deletesymbol']);
    Route::get('symbol/index', [WorkSymbolController::class, 'index'])->name('symbol.index');

    //***Management WorkDate***
    // Route::get('workdate', [WorkDateController::class, 'index']);
    // Route::post('workdate/postHoliday', [WorkDateController::class, 'postHoliday']);

    Route::post('workdate/create', [WorkDateController::class, 'addWorkdates']);
    Route::post('workdate/update', [WorkDateController::class, 'updateWorkdate']);
    Route::get('workdate/delete/{id}', [WorkDateController::class, 'deleteHoliday']);
    Route::get('workdate/index', [WorkDateController::class, 'index'])->name('workdate.index');
    Route::get('workdate/holiday', [WorkDateController::class, 'indexHoliday'])->name('workdate.holiday');;



    //***Management Employee***
    Route::get('employee/index', [EmployeeController::class, 'getEmployeeList']);
    Route::get('employee/add', [EmployeeController::class, 'create']);
    Route::post('employee/import', [EmployeeController::class, 'import']);
    Route::post('employee/add', [EmployeeController::class, 'addEmployee'])->name('Employee.Add');
    Route::post('employee/update', [EmployeeController::class, 'updateEmployee'])->name('Employee.Edit');
    Route::get('employee/delete/{id}', [EmployeeController::class, 'deleteEmployee'])->name('Employee.Delete');
    Route::post('employee/updateProfile', [EmployeeController::class, 'updateProfile']);
    Route::post('employee/importWork', [EmployeeController::class, 'importWorktime']);
    Route::get('employee/export', [EmployeeController::class, 'export']);
    Route::get('employee/personal', [EmployeeController::class, 'getEmployeePersonal']);
    Route::post('employee/updateperson', [EmployeeController::class, 'updatepersonal_coefficient']);


    //***Management User***

    Route::get('users/index', [UserController::class, 'getUserList']);
    Route::get('users/add', [UserController::class, 'create']);
    Route::post('users/add', [UserController::class, 'addUser'])->name('User.Add');
    Route::post('users/update', [UserController::class, 'updateUser'])->name('User.Edit');
    Route::get('users/delete/{id}', [UserController::class, 'deleteUser'])->name('User.Delete');
    Route::post('users/updateProfile', [UserController::class, 'updateProfile']);
    Route::get('users/profile', [UserController::class, 'getUserById']);
    Route::post('users/changPass', [UserController::class, 'changePassword']);



    //*****Management Import-Export*****
    // Route::post('/importEmployee',[EmployeeController::class, 'import_csv']);
    // Route::get('/exportEmployee',[EmployeeController::class, 'export_csv']);


    //*****Management Report Timesheet*****
    Route::get('report/index', [ReportTimesheet::class, 'index']);
    Route::get('report/{department_id}/{month}', [ReportTimesheet::class, 'getReport']);
    // Route::post('report/show', [ReportTimesheet::class, 'getReport']);

    Route::get('report/delete', [ReportTimesheet::class, 'delete']);
    Route::post('report/delete', [ReportTimesheet::class, 'deleteTimesheet']);
    //Route::post('report/timesheet', [ReportTimesheet::class, 'getReport']);
    Route::post('report/autoCalculate', [ReportTimesheet::class, 'finalReport']);

    Route::get('report/export/{department_id}/{month}', [ReportTimesheet::class, 'export']);
});
