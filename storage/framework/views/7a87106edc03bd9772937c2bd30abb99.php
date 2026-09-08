<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="<?php echo e(csrf_token()); ?>"
    >

    <title>
        IIT Indore | Faculty Attendance
    </title>

    <?php echo app('Illuminate\Foundation\Vite')([
        'resources/css/app.css',
        'resources/js/app.js'
    ]); ?>

    <style>

        .page {
            display: none;
        }

        .page.active {
            display: block;
        }

        .donut {
            transform: rotate(-90deg);
            transform-origin: center;
        }

        .progress-transition {
            transition: width 0.5s ease;
        }

        .attendance-row {
            transition:
                background-color 0.15s ease,
                transform 0.15s ease;
        }

        .attendance-row:hover {
            background-color: #f8fafc;
        }

        .attendance-row.absent {
            background-color: #fff7f7;
        }

    </style>

</head>


<body class="bg-slate-50 text-slate-900">


<div class="min-h-screen">


    
    
    

    <header
        class="sticky top-0 z-50 flex h-16
               items-center justify-between
               border-b border-slate-200
               bg-white px-4 lg:hidden"
    >

        <div class="flex items-center gap-3">

            <div
                class="flex h-9 w-9
                       items-center justify-center
                       rounded-lg
                       bg-[#8f1d2c]
                       text-xs font-bold text-white"
            >
                IIT
            </div>


            <div>

                <p class="text-sm font-bold">
                    IIT Indore
                </p>

                <p class="text-[10px] text-slate-400">
                    Faculty Portal
                </p>

            </div>

        </div>


        <button
            id="mobileMenuButton"
            type="button"
            class="flex h-9 w-9
                   items-center justify-center
                   rounded-lg
                   border border-slate-200
                   text-slate-600"
        >
            ☰
        </button>

    </header>



    
    
    

    <div
        id="mobileMenu"
        class="fixed inset-x-0 top-16 z-40 hidden
               border-b border-slate-200
               bg-white p-4 shadow-lg lg:hidden"
    >

        <nav class="space-y-1">

            <a
                href="#"
                class="block rounded-xl
                       px-4 py-3
                       text-sm text-slate-500
                       hover:bg-slate-50"
            >
                Dashboard
            </a>


            <a
                href="<?php echo e(route('attendance.index')); ?>"
                class="block rounded-xl
                       bg-[#8f1d2c]/10
                       px-4 py-3
                       text-sm font-semibold
                       text-[#8f1d2c]"
            >
                Attendance
            </a>


            <a
                href="#"
                class="block rounded-xl
                       px-4 py-3
                       text-sm text-slate-500
                       hover:bg-slate-50"
            >
                Students
            </a>


            <a
                href="#"
                class="block rounded-xl
                       px-4 py-3
                       text-sm text-slate-500
                       hover:bg-slate-50"
            >
                Reports
            </a>

        </nav>

    </div>



    
    
    

    <aside
        class="fixed inset-y-0 left-0 z-40
               hidden w-64
               border-r border-slate-200
               bg-white lg:block"
    >

        <div class="flex h-full flex-col">


            

            <div
                class="flex h-20
                       items-center gap-3
                       border-b border-slate-100
                       px-6"
            >

                <div
                    class="flex h-11 w-11
                           items-center
                           justify-center
                           rounded-xl
                           bg-[#8f1d2c]
                           text-sm font-bold
                           text-white"
                >
                    IIT
                </div>


                <div>

                    <p class="font-bold">
                        IIT Indore
                    </p>

                    <p class="text-[11px] text-slate-400">
                        Faculty Portal
                    </p>

                </div>

            </div>



            

            <nav class="flex-1 px-4 py-6">

                <p
                    class="mb-3 px-3
                           text-[10px]
                           font-bold
                           uppercase
                           tracking-wider
                           text-slate-400"
                >
                    Main Menu
                </p>


                <a
                    href="#"
                    class="mb-1 block
                           rounded-xl
                           px-3 py-3
                           text-sm text-slate-500
                           hover:bg-slate-50"
                >
                    Dashboard
                </a>


                <a
                    href="#"
                    class="mb-1 block
                           rounded-xl
                           px-3 py-3
                           text-sm text-slate-500
                           hover:bg-slate-50"
                >
                    My Courses
                </a>


                <a
                    href="<?php echo e(route('attendance.index')); ?>"
                    class="mb-1 block
                           rounded-xl
                           bg-[#8f1d2c]/10
                           px-3 py-3
                           text-sm font-semibold
                           text-[#8f1d2c]"
                >
                    Attendance
                </a>


                <a
                    href="#"
                    class="mb-1 block
                           rounded-xl
                           px-3 py-3
                           text-sm text-slate-500
                           hover:bg-slate-50"
                >
                    Students
                </a>


                <a
                    href="#"
                    class="block
                           rounded-xl
                           px-3 py-3
                           text-sm text-slate-500
                           hover:bg-slate-50"
                >
                    Reports
                </a>

            </nav>



            

            <div
                class="border-t
                       border-slate-100
                       p-4"
            >

                <div
                    class="flex items-center gap-3
                           rounded-xl
                           bg-slate-50 p-3"
                >

                    <div
                        class="flex h-10 w-10
                               items-center
                               justify-center
                               rounded-full
                               bg-[#8f1d2c]
                               text-sm font-bold
                               text-white"
                    >
                        F
                    </div>


                    <div class="min-w-0">

                        <p
                            id="facultyName"
                            class="truncate
                                   text-sm
                                   font-semibold"
                        >
                            Faculty
                        </p>


                        <p
                            id="facultyPosition"
                            class="truncate
                                   text-xs
                                   text-slate-400"
                        >
                            Faculty Member
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </aside>



    
    
    

    <div class="lg:pl-64">


        

        <header
            class="hidden h-20
                   items-center justify-between
                   border-b border-slate-200
                   bg-white px-8
                   lg:flex"
        >

            <div>

                <p class="text-xs text-slate-400">
                    Faculty Portal
                </p>

                <h1 class="text-lg font-bold">
                    Attendance Management
                </h1>

            </div>


            <div
                id="facultyInitial"
                class="flex h-10 w-10
                       items-center justify-center
                       rounded-full
                       bg-[#8f1d2c]
                       text-sm font-bold
                       text-white"
            >
                F
            </div>

        </header>



        <main
            class="mx-auto max-w-7xl
                   px-4 py-6
                   sm:px-6
                   lg:px-8 lg:py-8"
        >


            
            
            

            <section
                id="dashboardPage"
                class="page active"
            >


                

                <div class="mb-8">

                    <p
                        class="mb-2
                               text-xs font-semibold
                               uppercase tracking-wider
                               text-[#8f1d2c]"
                    >
                        Faculty Attendance
                    </p>


                    <h1
                        class="text-2xl font-bold
                               tracking-tight
                               sm:text-3xl"
                    >
                        Attendance Overview
                    </h1>


                    <p
                        class="mt-2
                               text-sm
                               text-slate-500"
                    >
                        Monitor attendance across all
                        courses you teach.
                    </p>

                </div>



                
                
                

                <div
                    class="mb-8 grid gap-5
                           lg:grid-cols-[1.1fr_0.9fr]"
                >


                    

                    <div
                        class="rounded-2xl
                               border
                               border-slate-200
                               bg-white
                               p-6 shadow-sm
                               sm:p-8"
                    >

                        <div
                            class="flex
                                   flex-col
                                   items-center
                                   justify-center
                                   gap-8
                                   sm:flex-row"
                        >


                            

                            <div
                                class="relative
                                       h-44 w-44
                                       shrink-0"
                            >

                                <svg
                                    viewBox="0 0 120 120"
                                    class="h-full w-full"
                                >

                                    <circle
                                        cx="60"
                                        cy="60"
                                        r="48"
                                        fill="none"
                                        stroke="#f1f5f9"
                                        stroke-width="12"
                                    />


                                    <circle
                                        id="overallDonut"
                                        class="donut"
                                        cx="60"
                                        cy="60"
                                        r="48"
                                        fill="none"
                                        stroke="#8f1d2c"
                                        stroke-width="12"
                                        stroke-linecap="round"
                                        stroke-dasharray="301.59"
                                        stroke-dashoffset="301.59"
                                    />

                                </svg>


                                <div
                                    class="absolute
                                           inset-0
                                           flex
                                           flex-col
                                           items-center
                                           justify-center"
                                >

                                    <span
                                        id="overallPercentage"
                                        class="text-3xl
                                               font-bold"
                                    >
                                        0%
                                    </span>


                                    <span
                                        class="text-[10px]
                                               text-slate-400"
                                    >
                                        Overall
                                    </span>

                                </div>

                            </div>



                            

                            <div class="w-full">

                                <p
                                    class="text-xs
                                           text-slate-400"
                                >
                                    Overall Attendance
                                </p>


                                <h2
                                    class="mt-1
                                           text-xl
                                           font-bold"
                                >
                                    All Courses
                                </h2>


                                <div
                                    class="mt-6 grid
                                           grid-cols-2
                                           gap-3"
                                >

                                    <div
                                        class="rounded-xl
                                               bg-slate-50
                                               p-4"
                                    >

                                        <p
                                            class="text-[11px]
                                                   text-slate-400"
                                        >
                                            Total Lectures
                                        </p>


                                        <p
                                            id="totalLectures"
                                            class="mt-1
                                                   text-xl
                                                   font-bold"
                                        >
                                            0
                                        </p>

                                    </div>


                                    <div
                                        class="rounded-xl
                                               bg-slate-50
                                               p-4"
                                    >

                                        <p
                                            class="text-[11px]
                                                   text-slate-400"
                                        >
                                            Courses
                                        </p>


                                        <p
                                            id="totalCourses"
                                            class="mt-1
                                                   text-xl
                                                   font-bold"
                                        >
                                            0
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    

                    <div
                        class="rounded-2xl
                               border
                               border-slate-200
                               bg-white
                               p-6 shadow-sm
                               sm:p-8"
                    >

                        <h2 class="font-bold">
                            Attendance Distribution
                        </h2>


                        <p
                            class="mt-1
                                   text-xs
                                   text-slate-400"
                        >
                            Overall attendance records
                        </p>


                        <div class="mt-6 space-y-5">


                            

                            <div>

                                <div
                                    class="mb-2 flex
                                           justify-between
                                           text-xs"
                                >

                                    <span
                                        class="text-slate-500"
                                    >
                                        Present
                                    </span>


                                    <span
                                        id="overallPresentText"
                                        class="font-bold
                                               text-emerald-600"
                                    >
                                        0
                                    </span>

                                </div>


                                <div
                                    class="h-3
                                           overflow-hidden
                                           rounded-full
                                           bg-slate-100"
                                >

                                    <div
                                        id="overallPresentBar"
                                        class="progress-transition
                                               h-full
                                               rounded-full
                                               bg-emerald-500"
                                        style="width:0%"
                                    ></div>

                                </div>

                            </div>



                            

                            <div>

                                <div
                                    class="mb-2 flex
                                           justify-between
                                           text-xs"
                                >

                                    <span
                                        class="text-slate-500"
                                    >
                                        Absent
                                    </span>


                                    <span
                                        id="overallAbsentText"
                                        class="font-bold
                                               text-red-600"
                                    >
                                        0
                                    </span>

                                </div>


                                <div
                                    class="h-3
                                           overflow-hidden
                                           rounded-full
                                           bg-slate-100"
                                >

                                    <div
                                        id="overallAbsentBar"
                                        class="progress-transition
                                               h-full
                                               rounded-full
                                               bg-red-400"
                                        style="width:0%"
                                    ></div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                
                
                

                <div>

                    <div class="mb-5">

                        <h2
                            class="text-lg
                                   font-bold"
                        >
                            Courses You Teach
                        </h2>


                        <p
                            class="mt-1
                                   text-xs
                                   text-slate-400"
                        >
                            Select a course to view
                            detailed attendance.
                        </p>

                    </div>


                    <div
                        id="courseContainer"
                        class="grid gap-5
                               sm:grid-cols-2
                               xl:grid-cols-3"
                    >
                    </div>

                </div>

            </section>



            
            
            

            <section
                id="coursePage"
                class="page"
            >


                <button
                    id="courseBackButton"
                    type="button"
                    class="mb-6 flex
                           items-center gap-2
                           text-sm font-medium
                           text-slate-500
                           hover:text-[#8f1d2c]"
                >
                    ← All Courses
                </button>



                <div
                    class="mb-7 flex
                           flex-col gap-5
                           lg:flex-row
                           lg:items-end
                           lg:justify-between"
                >

                    <div>

                        <span
                            id="coursePageCode"
                            class="inline-flex
                                   rounded-lg
                                   bg-[#8f1d2c]/10
                                   px-3 py-1
                                   text-xs font-bold
                                   text-[#8f1d2c]"
                        >
                        </span>


                        <h1
                            id="coursePageName"
                            class="mt-3 text-2xl
                                   font-bold
                                   sm:text-3xl"
                        >
                        </h1>


                        <p
                            class="mt-2 text-sm
                                   text-slate-500"
                        >
                            Course attendance overview
                        </p>

                    </div>



                    <button
                        id="courseAddButton"
                        type="button"
                        class="flex items-center
                               justify-center
                               gap-2 rounded-xl
                               bg-[#8f1d2c]
                               px-5 py-3
                               text-sm font-semibold
                               text-white
                               hover:bg-[#741622]"
                    >

                        <span class="text-lg">
                            +
                        </span>

                        Add Attendance

                    </button>

                </div>



                

                <div
                    class="mb-7 grid gap-5
                           lg:grid-cols-[0.9fr_1.1fr]"
                >


                    

                    <div
                        class="rounded-2xl
                               border
                               border-slate-200
                               bg-white
                               p-6 shadow-sm"
                    >

                        <div
                            class="flex
                                   flex-col
                                   items-center"
                        >

                            <div
                                class="relative
                                       h-48 w-48"
                            >

                                <svg
                                    viewBox="0 0 120 120"
                                    class="h-full w-full"
                                >

                                    <circle
                                        cx="60"
                                        cy="60"
                                        r="48"
                                        fill="none"
                                        stroke="#f1f5f9"
                                        stroke-width="12"
                                    />


                                    <circle
                                        id="courseDonut"
                                        class="donut"
                                        cx="60"
                                        cy="60"
                                        r="48"
                                        fill="none"
                                        stroke="#8f1d2c"
                                        stroke-width="12"
                                        stroke-linecap="round"
                                        stroke-dasharray="301.59"
                                        stroke-dashoffset="301.59"
                                    />

                                </svg>


                                <div
                                    class="absolute
                                           inset-0
                                           flex
                                           flex-col
                                           items-center
                                           justify-center"
                                >

                                    <span
                                        id="coursePercentage"
                                        class="text-3xl
                                               font-bold"
                                    >
                                        0%
                                    </span>


                                    <span
                                        class="text-[10px]
                                               text-slate-400"
                                    >
                                        Attendance
                                    </span>

                                </div>

                            </div>


                            <p
                                id="courseAttendanceLabel"
                                class="mt-4 text-sm
                                       text-slate-500"
                            >
                                0 present records
                            </p>

                        </div>

                    </div>



                    

                    <div
                        class="rounded-2xl
                               border
                               border-slate-200
                               bg-white
                               p-6 shadow-sm"
                    >

                        <div class="mb-6">

                            <h2 class="font-bold">
                                Lecture-wise Attendance
                            </h2>


                            <p
                                class="mt-1 text-xs
                                       text-slate-400"
                            >
                                Attendance percentage
                                for each lecture.
                            </p>

                        </div>


                        <div
                            id="lectureGraph"
                            class="space-y-5"
                        >
                        </div>

                    </div>

                </div>



                
                
                

                <div
                    class="overflow-hidden
                           rounded-2xl
                           border border-slate-200
                           bg-white shadow-sm"
                >

                    <div
                        class="border-b
                               border-slate-200
                               px-5 py-5
                               sm:px-6"
                    >

                        <h2 class="font-bold">
                            Previous Attendance
                        </h2>


                        <p
                            class="mt-1 text-xs
                                   text-slate-400"
                        >
                            Students are hidden until
                            you click Edit.
                        </p>

                    </div>



                    

                    <div
                        id="courseLectureTable"
                        class="hidden md:block"
                    >
                    </div>



                    

                    <div
                        id="courseLectureMobile"
                        class="divide-y
                               divide-slate-100
                               md:hidden"
                    >
                    </div>

                </div>

            </section>



            
            
            

            <section
                id="editorPage"
                class="page"
            >


                <button
                    id="editorBackButton"
                    type="button"
                    class="mb-6 flex
                           items-center gap-2
                           text-sm font-medium
                           text-slate-500
                           hover:text-[#8f1d2c]"
                >
                    ← Back to Course
                </button>



                

                <div
                    class="mb-6 flex
                           flex-col gap-5
                           lg:flex-row
                           lg:items-end
                           lg:justify-between"
                >

                    <div>

                        <span
                            id="editorCourseBadge"
                            class="rounded-lg
                                   bg-[#8f1d2c]/10
                                   px-3 py-1
                                   text-xs font-bold
                                   text-[#8f1d2c]"
                        >
                        </span>


                        <h1
                            id="editorTitle"
                            class="mt-3 text-2xl
                                   font-bold
                                   sm:text-3xl"
                        >
                            Add Attendance
                        </h1>


                        <p
                            class="mt-2 text-sm
                                   text-slate-500"
                        >
                            All students start as present.
                            Click students to mark them absent.
                        </p>

                    </div>



                    

                    <div
                        class="grid
                               grid-cols-2
                               gap-3"
                    >

                        <div
                            class="rounded-xl
                                   border
                                   border-emerald-100
                                   bg-emerald-50
                                   px-5 py-3
                                   text-center"
                        >

                            <p
                                class="text-[11px]
                                       text-emerald-600"
                            >
                                Present
                            </p>


                            <p
                                id="presentCounter"
                                class="mt-1
                                       text-xl
                                       font-bold
                                       text-emerald-700"
                            >
                                0
                            </p>

                        </div>


                        <div
                            class="rounded-xl
                                   border
                                   border-red-100
                                   bg-red-50
                                   px-5 py-3
                                   text-center"
                        >

                            <p
                                class="text-[11px]
                                       text-red-600"
                            >
                                Absent
                            </p>


                            <p
                                id="absentCounter"
                                class="mt-1
                                       text-xl
                                       font-bold
                                       text-red-700"
                            >
                                0
                            </p>

                        </div>

                    </div>

                </div>



                
                
                

                <div
                    class="mb-5 rounded-2xl
                           border border-slate-200
                           bg-white p-5
                           shadow-sm sm:p-6"
                >

                    <h2 class="font-bold">
                        Lecture Details
                    </h2>


                    <div
                        class="mt-5 grid
                               gap-5
                               md:grid-cols-2
                               lg:grid-cols-4"
                    >


                        

                        <div
                            class="lg:col-span-2"
                        >

                            <label
                                class="mb-2 block
                                       text-xs
                                       font-bold
                                       text-slate-600"
                            >
                                Course
                            </label>


                            <select
                                id="courseSelect"
                                class="w-full
                                       rounded-xl
                                       border
                                       border-slate-200
                                       bg-white
                                       px-4 py-3
                                       text-sm
                                       outline-none
                                       focus:border-[#8f1d2c]"
                            >

                                <option value="">
                                    Select Course
                                </option>

                            </select>

                        </div>



                        

                        <div>

                            <label
                                class="mb-2 block
                                       text-xs
                                       font-bold
                                       text-slate-600"
                            >
                                Lecture Number
                            </label>


                            <input
                                id="lectureNumber"
                                type="number"
                                class="w-full
                                       rounded-xl
                                       border
                                       border-slate-200
                                       bg-slate-50
                                       px-4 py-3
                                       text-sm
                                       font-semibold
                                       outline-none"
                            >

                        </div>



                        

                        <div>

                            <label
                                class="mb-2 block
                                       text-xs
                                       font-bold
                                       text-slate-600"
                            >
                                Date
                            </label>


                            <input
                                id="lectureDate"
                                type="date"
                                class="w-full
                                       rounded-xl
                                       border
                                       border-slate-200
                                       px-4 py-3
                                       text-sm
                                       outline-none
                                       focus:border-[#8f1d2c]"
                            >

                        </div>



                        

                        <div
                            class="md:col-span-2
                                   lg:col-span-4"
                        >

                            <label
                                class="mb-2 block
                                       text-xs
                                       font-bold
                                       text-slate-600"
                            >
                                Lecture Name / Topic
                            </label>


                            <input
                                id="lectureName"
                                type="text"
                                placeholder="Example: Binary Search Trees"
                                class="w-full
                                       rounded-xl
                                       border
                                       border-slate-200
                                       px-4 py-3
                                       text-sm
                                       outline-none
                                       focus:border-[#8f1d2c]"
                            >

                        </div>

                    </div>

                </div>



                
                
                

                <div
                    class="overflow-hidden
                           rounded-2xl
                           border
                           border-slate-200
                           bg-white
                           shadow-sm"
                >

                    <div
                        class="flex
                               flex-col
                               gap-4
                               border-b
                               border-slate-200
                               p-5
                               sm:p-6
                               md:flex-row
                               md:items-center
                               md:justify-between"
                    >

                        <div>

                            <h2 class="font-bold">
                                Student Attendance
                            </h2>


                            <p
                                class="mt-1 text-xs
                                       text-slate-400"
                            >
                                Click a student to mark them
                                absent or present.
                            </p>

                        </div>


                        <input
                            id="studentSearch"
                            type="text"
                            placeholder="Search student..."
                            class="w-full
                                   rounded-xl
                                   border
                                   border-slate-200
                                   px-4 py-3
                                   text-sm
                                   outline-none
                                   focus:border-[#8f1d2c]
                                   md:w-72"
                        >

                    </div>



                    <div
                        class="flex
                               items-center
                               justify-between
                               border-b
                               border-slate-100
                               bg-slate-50
                               px-5 py-3
                               text-xs sm:px-6"
                    >

                        <span
                            class="text-slate-500"
                        >
                            Students enrolled
                        </span>


                        <span
                            id="studentCount"
                            class="font-bold"
                        >
                            0
                        </span>

                    </div>



                    <div
                        id="studentContainer"
                        class="divide-y
                               divide-slate-100"
                    >
                    </div>



                    

                    <div
                        class="flex
                               flex-col-reverse
                               gap-3
                               border-t
                               border-slate-200
                               bg-slate-50
                               p-5
                               sm:flex-row
                               sm:justify-end
                               sm:px-6"
                    >

                        <button
                            id="cancelButton"
                            type="button"
                            class="rounded-xl
                                   border
                                   border-slate-200
                                   bg-white
                                   px-6 py-3
                                   text-sm
                                   font-semibold
                                   text-slate-600"
                        >
                            Cancel
                        </button>


                        <button
                            id="saveButton"
                            type="button"
                            class="rounded-xl
                                   bg-[#8f1d2c]
                                   px-6 py-3
                                   text-sm
                                   font-semibold
                                   text-white
                                   hover:bg-[#741622]"
                        >
                            Save Attendance
                        </button>

                    </div>

                </div>

            </section>

        </main>

    </div>



<script>

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

let courses = [];

let lectures = [];

let selectedCourseId = null;

let editingLectureId = null;

let attendance = {};

let editing = false;


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

const csrfToken =
    document
        .querySelector(
            'meta[name="csrf-token"]'
        )
        .getAttribute('content');


/*
|--------------------------------------------------------------------------
| API base URLs
|--------------------------------------------------------------------------
*/

const attendanceDataUrl =
    <?php echo json_encode(route('attendance.data'), 15, 512) ?>;

const attendanceCourseUrl =
    <?php echo json_encode(url('/api/attendance/course'), 15, 512) ?>;

const attendanceLectureUrl =
    <?php echo json_encode(url('/api/attendance/lecture'), 15, 512) ?>;


/*
|--------------------------------------------------------------------------
| DOM elements
|--------------------------------------------------------------------------
*/

const dashboardPage =
    document.getElementById(
        'dashboardPage'
    );

const coursePage =
    document.getElementById(
        'coursePage'
    );

const editorPage =
    document.getElementById(
        'editorPage'
    );


const courseContainer =
    document.getElementById(
        'courseContainer'
    );

const lectureGraph =
    document.getElementById(
        'lectureGraph'
    );

const courseLectureTable =
    document.getElementById(
        'courseLectureTable'
    );

const courseLectureMobile =
    document.getElementById(
        'courseLectureMobile'
    );

const courseSelect =
    document.getElementById(
        'courseSelect'
    );

const lectureNumber =
    document.getElementById(
        'lectureNumber'
    );

const lectureName =
    document.getElementById(
        'lectureName'
    );

const lectureDate =
    document.getElementById(
        'lectureDate'
    );

const studentSearch =
    document.getElementById(
        'studentSearch'
    );

const studentContainer =
    document.getElementById(
        'studentContainer'
    );

const presentCounter =
    document.getElementById(
        'presentCounter'
    );

const absentCounter =
    document.getElementById(
        'absentCounter'
    );


/*
|--------------------------------------------------------------------------
| Utility
|--------------------------------------------------------------------------
*/

function showPage(page) {

    dashboardPage.classList.remove(
        'active'
    );

    coursePage.classList.remove(
        'active'
    );

    editorPage.classList.remove(
        'active'
    );


    page.classList.add(
        'active'
    );


    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });

}


/*
|--------------------------------------------------------------------------
| Get course
|--------------------------------------------------------------------------
*/

function getCourse(courseId) {

    return courses.find(
        course =>
            Number(course.id) ===
            Number(courseId)
    );

}


/*
|--------------------------------------------------------------------------
| Get lectures for course
|--------------------------------------------------------------------------
*/

function getCourseLectures(courseId) {

    return lectures
        .filter(
            lecture =>
                Number(lecture.courseId) ===
                Number(courseId)
        )
        .sort(
            (a, b) =>
                Number(b.number) -
                Number(a.number)
        );

}


/*
|--------------------------------------------------------------------------
| Lecture statistics
|--------------------------------------------------------------------------
*/

function getLectureStats(lecture) {

    const course =
        getCourse(
            lecture.courseId
        );


    if (!course) {

        return {
            total: 0,
            present: 0,
            absent: 0,
            percentage: 0
        };

    }


    const total =
        course.students.length;


    const present =
        course.students.filter(
            student =>
                lecture.attendance[
                    student.id
                ] === true
        ).length;


    const absent =
        total - present;


    const percentage =
        total === 0
            ? 0
            : Math.round(
                (
                    present /
                    total
                ) * 100
            );


    return {
        total,
        present,
        absent,
        percentage
    };

}


/*
|--------------------------------------------------------------------------
| Course statistics
|--------------------------------------------------------------------------
*/

function getCourseStats(courseId) {

    const courseLectures =
        getCourseLectures(
            courseId
        );


    let present = 0;

    let possible = 0;


    courseLectures.forEach(
        lecture => {

            const stats =
                getLectureStats(
                    lecture
                );


            present +=
                stats.present;


            possible +=
                stats.total;

        }
    );


    return {

        present,

        possible,

        absent:
            possible - present,

        percentage:
            possible === 0
                ? 0
                : Math.round(
                    (
                        present /
                        possible
                    ) * 100
                ),

        lectures:
            courseLectures.length

    };

}


/*
|--------------------------------------------------------------------------
| Overall statistics
|--------------------------------------------------------------------------
*/

function getOverallStats() {

    let present = 0;

    let possible = 0;


    lectures.forEach(
        lecture => {

            const stats =
                getLectureStats(
                    lecture
                );


            present +=
                stats.present;


            possible +=
                stats.total;

        }
    );


    return {

        present,

        possible,

        absent:
            possible - present,

        percentage:
            possible === 0
                ? 0
                : Math.round(
                    (
                        present /
                        possible
                    ) * 100
                )

    };

}


/*
|--------------------------------------------------------------------------
| Donut
|--------------------------------------------------------------------------
*/

function setDonut(
    element,
    percentage
) {

    const circumference =
        2 * Math.PI * 48;


    const offset =
        circumference -
        (
            percentage /
            100
        ) *
        circumference;


    element.style.strokeDashoffset =
        offset;

}


/*
|--------------------------------------------------------------------------
| Load data from Laravel
|--------------------------------------------------------------------------
*/

async function loadAttendanceData() {

    try {

        const response =
            await fetch(
                attendanceDataUrl,
                {
                    headers: {
                        'Accept':
                            'application/json'
                    }
                }
            );


        if (!response.ok) {

            throw new Error(
                'Failed to load attendance data.'
            );

        }


        const data =
            await response.json();


        courses =
            data.courses ?? [];


        lectures =
            data.lectures ?? [];


        /*
        |--------------------------------------------------------------------------
        | Faculty information
        |--------------------------------------------------------------------------
        */

        if (data.faculty) {

            const facultyName =
                document.getElementById(
                    'facultyName'
                );

            const facultyPosition =
                document.getElementById(
                    'facultyPosition'
                );

            const facultyInitial =
                document.getElementById(
                    'facultyInitial'
                );


            facultyName.textContent =
                data.faculty.name ??
                'Faculty';


            facultyPosition.textContent =
                data.faculty.position ??
                'Faculty Member';


            const initial =
                data.faculty.name
                    ? data.faculty.name
                        .trim()
                        .charAt(0)
                        .toUpperCase()
                    : 'F';


            facultyInitial.textContent =
                initial;

        }


        populateCourseSelect();

        renderDashboard();

    } catch (error) {

        console.error(error);


        courseContainer.innerHTML = `

            <div
                class="col-span-full
                       rounded-2xl
                       border border-red-200
                       bg-red-50
                       p-6"
            >

                <p
                    class="font-semibold
                           text-red-700"
                >
                    Unable to load attendance data.
                </p>

                <p
                    class="mt-1 text-sm
                           text-red-600"
                >
                    ${escapeHtml(
                        error.message
                    )}
                </p>

            </div>

        `;

    }

}


/*
|--------------------------------------------------------------------------
| Escape HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value) {

    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

}


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

function renderDashboard() {

    const stats =
        getOverallStats();


    document
        .getElementById(
            'overallPercentage'
        )
        .textContent =
        `${stats.percentage}%`;


    setDonut(

        document.getElementById(
            'overallDonut'
        ),

        stats.percentage

    );


    document
        .getElementById(
            'totalLectures'
        )
        .textContent =
        lectures.length;


    document
        .getElementById(
            'totalCourses'
        )
        .textContent =
        courses.length;


    document
        .getElementById(
            'overallPresentText'
        )
        .textContent =
        stats.present;


    document
        .getElementById(
            'overallAbsentText'
        )
        .textContent =
        stats.absent;


    const total =
        stats.possible || 1;


    document
        .getElementById(
            'overallPresentBar'
        )
        .style.width =
        `${
            (
                stats.present /
                total
            ) * 100
        }%`;


    document
        .getElementById(
            'overallAbsentBar'
        )
        .style.width =
        `${
            (
                stats.absent /
                total
            ) * 100
        }%`;


    renderCourses();

}


/*
|--------------------------------------------------------------------------
| Course cards
|--------------------------------------------------------------------------
*/

function renderCourses() {

    courseContainer.innerHTML = '';


    if (!courses.length) {

        courseContainer.innerHTML = `

            <div
                class="col-span-full
                       rounded-2xl
                       border border-slate-200
                       bg-white p-10
                       text-center"
            >

                <p
                    class="font-semibold"
                >
                    No courses found
                </p>

                <p
                    class="mt-1 text-sm
                           text-slate-400"
                >
                    No courses are currently
                    assigned to you.
                </p>

            </div>

        `;

        return;

    }


    courses.forEach(
        course => {

            const stats =
                getCourseStats(
                    course.id
                );


            const card =
                document.createElement(
                    'button'
                );


            card.type =
                'button';


            card.className =

                'group rounded-2xl ' +
                'border border-slate-200 ' +
                'bg-white p-5 text-left ' +
                'shadow-sm transition ' +
                'hover:-translate-y-1 ' +
                'hover:border-[#8f1d2c]/30 ' +
                'hover:shadow-lg cursor-pointer';


            card.innerHTML = `

                <div
                    class="flex items-start
                           justify-between"
                >

                    <div>

                        <span
                            class="rounded-lg
                                   bg-[#8f1d2c]/10
                                   px-2.5 py-1
                                   text-[11px]
                                   font-bold
                                   text-[#8f1d2c]"
                        >
                            ${escapeHtml(
                                course.code
                            )}
                        </span>


                        <h3
                            class="mt-3
                                   font-bold"
                        >
                            ${escapeHtml(
                                course.name
                            )}
                        </h3>

                    </div>


                    <div
                        class="flex h-10 w-10
                               items-center
                               justify-center
                               rounded-xl
                               bg-slate-50
                               text-lg"
                    >
                        →
                    </div>

                </div>


                <div class="mt-6">

                    <div
                        class="mb-2 flex
                               items-end
                               justify-between"
                    >

                        <div>

                            <span
                                class="text-2xl
                                       font-bold"
                            >
                                ${stats.percentage}%
                            </span>


                            <span
                                class="ml-1
                                       text-xs
                                       text-slate-400"
                            >
                                attendance
                            </span>

                        </div>


                        <span
                            class="text-xs
                                   text-slate-400"
                        >
                            ${stats.lectures}
                            lectures
                        </span>

                    </div>


                    <div
                        class="h-2 overflow-hidden
                               rounded-full
                               bg-slate-100"
                    >

                        <div
                            class="h-full
                                   rounded-full
                                   bg-[#8f1d2c]"
                            style="
                                width:
                                ${stats.percentage}%
                            "
                        ></div>

                    </div>

                </div>


                <div
                    class="mt-5 flex
                           items-center
                           justify-between
                           border-t
                           border-slate-100
                           pt-4"
                >

                    <span
                        class="text-xs
                               text-slate-400"
                    >
                        ${
                            course.students.length
                        }
                        students
                    </span>


                    <span
                        class="text-xs
                               font-bold
                               text-[#8f1d2c]"
                    >
                        View Course →
                    </span>

                </div>

            `;


            card.addEventListener(
                'click',
                () => {

                    openCourse(
                        course.id
                    );

                }
            );


            courseContainer.appendChild(
                card
            );

        }
    );

}


/*
|--------------------------------------------------------------------------
| Populate course select
|--------------------------------------------------------------------------
*/

function populateCourseSelect() {

    courseSelect.innerHTML = `

        <option value="">
            Select Course
        </option>

    `;


    courses.forEach(
        course => {

            const option =
                document.createElement(
                    'option'
                );


            option.value =
                course.id;


            option.textContent =
                `${course.code} — ${course.name}`;


            courseSelect.appendChild(
                option
            );

        }
    );

}


/*
|--------------------------------------------------------------------------
| Open course
|--------------------------------------------------------------------------
*/

function openCourse(courseId) {

    selectedCourseId =
        Number(courseId);


    const course =
        getCourse(
            selectedCourseId
        );


    if (!course) {

        return;

    }


    document
        .getElementById(
            'coursePageCode'
        )
        .textContent =
        course.code;


    document
        .getElementById(
            'coursePageName'
        )
        .textContent =
        course.name;


    renderCoursePage();

    showPage(
        coursePage
    );

}


/*
|--------------------------------------------------------------------------
| Course page
|--------------------------------------------------------------------------
*/

function renderCoursePage() {

    const stats =
        getCourseStats(
            selectedCourseId
        );


    document
        .getElementById(
            'coursePercentage'
        )
        .textContent =
        `${stats.percentage}%`;


    setDonut(

        document.getElementById(
            'courseDonut'
        ),

        stats.percentage

    );


    document
        .getElementById(
            'courseAttendanceLabel'
        )
        .textContent =
        `${stats.present} present records out of ${stats.possible}`;


    renderLectureGraph();

    renderCourseLectures();

}


/*
|--------------------------------------------------------------------------
| Lecture graph
|--------------------------------------------------------------------------
*/

function renderLectureGraph() {

    const courseLectures =
        getCourseLectures(
            selectedCourseId
        );


    if (!courseLectures.length) {

        lectureGraph.innerHTML = `

            <p class="text-sm text-slate-400">

                No lectures recorded yet.

            </p>

        `;

        return;

    }


    lectureGraph.innerHTML =
        courseLectures
            .slice(0, 8)
            .map(
                lecture => {

                    const stats =
                        getLectureStats(
                            lecture
                        );


                    return `

                        <div>

                            <div
                                class="mb-2 flex
                                       justify-between
                                       gap-3 text-xs"
                            >

                                <div
                                    class="min-w-0"
                                >

                                    <span
                                        class="font-semibold"
                                    >
                                        L${lecture.number}
                                    </span>


                                    <span
                                        class="ml-2
                                               truncate
                                               text-slate-400"
                                    >
                                        ${escapeHtml(
                                            lecture.name
                                        )}
                                    </span>

                                </div>


                                <span
                                    class="shrink-0
                                           font-bold"
                                >
                                    ${stats.percentage}%
                                </span>

                            </div>


                            <div
                                class="h-2.5
                                       overflow-hidden
                                       rounded-full
                                       bg-slate-100"
                            >

                                <div
                                    class="h-full
                                           rounded-full
                                           ${
                                               stats.percentage >= 75
                                                   ? 'bg-emerald-500'
                                                   : 'bg-red-400'
                                           }"
                                    style="
                                        width:
                                        ${stats.percentage}%
                                    "
                                ></div>

                            </div>

                        </div>

                    `;

                }
            )
            .join('');

}


/*
|--------------------------------------------------------------------------
| Previous lectures
|--------------------------------------------------------------------------
*/

function renderCourseLectures() {

    const courseLectures =
        getCourseLectures(
            selectedCourseId
        );


    /*
    |--------------------------------------------------------------------------
    | Desktop
    |--------------------------------------------------------------------------
    */

    courseLectureTable.innerHTML = `

        <table class="w-full">

            <thead>

                <tr
                    class="border-b
                           border-slate-100
                           bg-slate-50
                           text-left
                           text-[10px]
                           uppercase
                           tracking-wider
                           text-slate-400"
                >

                    <th class="px-6 py-4">
                        Lecture
                    </th>

                    <th class="px-6 py-4">
                        Topic
                    </th>

                    <th class="px-6 py-4">
                        Date
                    </th>

                    <th class="px-6 py-4">
                        Attendance
                    </th>

                    <th class="px-6 py-4 text-right">
                        Action
                    </th>

                </tr>

            </thead>


            <tbody
                class="divide-y
                       divide-slate-100"
            >

                ${
                    courseLectures.length

                    ?

                    courseLectures
                        .map(
                            lecture => {

                                const stats =
                                    getLectureStats(
                                        lecture
                                    );


                                return `

                                    <tr
                                        class="hover:bg-slate-50"
                                    >

                                        <td
                                            class="px-6 py-5"
                                        >

                                            <span
                                                class="rounded-lg
                                                       bg-slate-100
                                                       px-3 py-1
                                                       text-xs
                                                       font-bold"
                                            >
                                                Lecture
                                                ${lecture.number}
                                            </span>

                                        </td>


                                        <td
                                            class="px-6 py-5
                                                   text-sm
                                                   font-semibold"
                                        >
                                            ${escapeHtml(
                                                lecture.name
                                            )}
                                        </td>


                                        <td
                                            class="px-6 py-5
                                                   text-sm
                                                   text-slate-500"
                                        >
                                            ${formatDate(
                                                lecture.date
                                            )}
                                        </td>


                                        <td
                                            class="px-6 py-5"
                                        >

                                            <div
                                                class="flex
                                                       items-center
                                                       gap-3"
                                            >

                                                <div
                                                    class="h-2 w-20
                                                           overflow-hidden
                                                           rounded-full
                                                           bg-slate-100"
                                                >

                                                    <div
                                                        class="h-full
                                                               rounded-full
                                                               ${
                                                                   stats.percentage >= 75
                                                                       ? 'bg-emerald-500'
                                                                       : 'bg-red-400'
                                                               }"
                                                        style="
                                                            width:
                                                            ${stats.percentage}%
                                                        "
                                                    ></div>

                                                </div>


                                                <span
                                                    class="text-xs
                                                           font-bold"
                                                >
                                                    ${stats.percentage}%
                                                </span>

                                            </div>

                                        </td>


                                        <td
                                            class="px-6 py-5
                                                   text-right"
                                        >

                                            <button
                                                type="button"
                                                data-edit-id="${lecture.id}"
                                                class="editLectureButton
                                                       rounded-lg
                                                       border
                                                       border-slate-200
                                                       px-4 py-2
                                                       text-xs
                                                       font-bold
                                                       hover:border-[#8f1d2c]
                                                       hover:text-[#8f1d2c]"
                                            >
                                                Edit
                                            </button>

                                        </td>

                                    </tr>

                                `;

                            }
                        )
                        .join('')

                    :

                    `

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-12
                                       text-center
                                       text-sm
                                       text-slate-400"
                            >
                                No attendance records found.
                            </td>

                        </tr>

                    `

                }

            </tbody>

        </table>

    `;


    /*
    |--------------------------------------------------------------------------
    | Mobile
    |--------------------------------------------------------------------------
    */

    courseLectureMobile.innerHTML =

        courseLectures.length

            ?

            courseLectures
                .map(
                    lecture => {

                        const stats =
                            getLectureStats(
                                lecture
                            );


                        return `

                            <div class="p-5">

                                <div
                                    class="flex
                                           items-start
                                           justify-between
                                           gap-3"
                                >

                                    <div>

                                        <p
                                            class="text-[10px]
                                                   font-bold
                                                   text-[#8f1d2c]"
                                        >
                                            Lecture
                                            ${lecture.number}
                                        </p>


                                        <h3
                                            class="mt-1
                                                   text-sm
                                                   font-bold"
                                        >
                                            ${escapeHtml(
                                                lecture.name
                                            )}
                                        </h3>


                                        <p
                                            class="mt-1
                                                   text-xs
                                                   text-slate-400"
                                        >
                                            ${formatDate(
                                                lecture.date
                                            )}
                                        </p>

                                    </div>


                                    <button
                                        type="button"
                                        data-edit-id="${lecture.id}"
                                        class="editLectureButton
                                               shrink-0
                                               rounded-lg
                                               border
                                               border-slate-200
                                               px-3 py-2
                                               text-xs
                                               font-bold"
                                    >
                                        Edit
                                    </button>

                                </div>


                                <div class="mt-4">

                                    <div
                                        class="mb-2 flex
                                               justify-between
                                               text-xs"
                                    >

                                        <span
                                            class="text-slate-400"
                                        >
                                            Attendance
                                        </span>


                                        <span
                                            class="font-bold"
                                        >
                                            ${stats.present}/
                                            ${stats.total}
                                            ·
                                            ${stats.percentage}%
                                        </span>

                                    </div>


                                    <div
                                        class="h-2
                                               overflow-hidden
                                               rounded-full
                                               bg-slate-100"
                                    >

                                        <div
                                            class="h-full
                                                   rounded-full
                                                   ${
                                                       stats.percentage >= 75
                                                           ? 'bg-emerald-500'
                                                           : 'bg-red-400'
                                                   }"
                                            style="
                                                width:
                                                ${stats.percentage}%
                                            "
                                        ></div>

                                    </div>

                                </div>

                            </div>

                        `;

                    }
                )
                .join('')

            :

            `

                <div
                    class="p-10 text-center
                           text-sm text-slate-400"
                >
                    No attendance records found.
                </div>

            `;


    /*
    |--------------------------------------------------------------------------
    | Attach edit buttons
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.editLectureButton'
        )
        .forEach(
            button => {

                button.addEventListener(
                    'click',
                    () => {

                        openEdit(
                            Number(
                                button.dataset.editId
                            )
                        );

                    }
                );

            }
        );

}


/*
|--------------------------------------------------------------------------
| Open Add Attendance
|--------------------------------------------------------------------------
*/

function openAdd(courseId) {

    const course =
        getCourse(
            courseId
        );


    if (!course) {

        return;

    }


    editing =
        false;


    editingLectureId =
        null;


    selectedCourseId =
        Number(courseId);


    /*
    |--------------------------------------------------------------------------
    | Find next lecture number
    |--------------------------------------------------------------------------
    */

    const existingLectures =
        getCourseLectures(
            courseId
        );


    const nextLectureNumber =
        existingLectures.length
            ? Math.max(
                ...existingLectures.map(
                    lecture =>
                        Number(
                            lecture.number
                        )
                )
            ) + 1
            : 1;


    /*
    |--------------------------------------------------------------------------
    | Everyone starts as present
    |--------------------------------------------------------------------------
    */

    attendance = {};


    course.students.forEach(
        student => {

            attendance[
                student.id
            ] = true;

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Fill form
    |--------------------------------------------------------------------------
    */

    document
        .getElementById(
            'editorTitle'
        )
        .textContent =
        'Add Attendance';


    document
        .getElementById(
            'editorCourseBadge'
        )
        .textContent =
        course.code;


    courseSelect.value =
        course.id;


    courseSelect.disabled =
        true;


    lectureNumber.value =
        nextLectureNumber;


    lectureNumber.disabled =
        true;


    lectureName.value =
        '';


    lectureDate.value =
        new Date()
            .toISOString()
            .split('T')[0];


    studentSearch.value =
        '';


    showEditor();

}


/*
|--------------------------------------------------------------------------
| Open Edit
|--------------------------------------------------------------------------
*/

function openEdit(lectureId) {

    const lecture =
        lectures.find(
            item =>
                Number(item.id) ===
                Number(lectureId)
        );


    if (!lecture) {

        return;

    }


    const course =
        getCourse(
            lecture.courseId
        );


    if (!course) {

        return;

    }


    editing =
        true;


    editingLectureId =
        Number(
            lecture.id
        );


    selectedCourseId =
        Number(
            lecture.courseId
        );


    attendance =
        {
            ...lecture.attendance
        };


    document
        .getElementById(
            'editorTitle'
        )
        .textContent =
        'Edit Attendance';


    document
        .getElementById(
            'editorCourseBadge'
        )
        .textContent =
        course.code;


    courseSelect.value =
        course.id;


    courseSelect.disabled =
        true;


    lectureNumber.value =
        lecture.number;


    lectureNumber.disabled =
        true;


    lectureName.value =
        lecture.name;


    lectureDate.value =
        lecture.date;


    studentSearch.value =
        '';


    showEditor();

}


/*
|--------------------------------------------------------------------------
| Show editor
|--------------------------------------------------------------------------
*/

function showEditor() {

    renderStudents();

    updateCounters();

    showPage(
        editorPage
    );

}


/*
|--------------------------------------------------------------------------
| Render students
|--------------------------------------------------------------------------
*/

function renderStudents() {

    const course =
        getCourse(
            selectedCourseId
        );


    if (!course) {

        studentContainer.innerHTML = '';

        return;

    }


    const query =
        studentSearch.value
            .toLowerCase()
            .trim();


    const filteredStudents =
        course.students.filter(
            student =>

                student.name
                    .toLowerCase()
                    .includes(query)

                ||

                String(
                    student.roll
                )
                    .toLowerCase()
                    .includes(query)

        );


    document
        .getElementById(
            'studentCount'
        )
        .textContent =
        course.students.length;


    if (!filteredStudents.length) {

        studentContainer.innerHTML = `

            <div
                class="p-10
                       text-center"
            >

                <p
                    class="text-sm
                           font-semibold"
                >
                    No students found
                </p>

                <p
                    class="mt-1
                           text-xs
                           text-slate-400"
                >
                    Try another name or roll number.
                </p>

            </div>

        `;

        return;

    }


    studentContainer.innerHTML =
        filteredStudents
            .map(
                student => {

                    const present =
                        attendance[
                            student.id
                        ] === true;


                    return `

                        <button
                            type="button"
                            data-student-id="${escapeHtml(
                                student.id
                            )}"
                            class="studentRow
                                   attendance-row
                                   grid w-full
                                   grid-cols-[1fr_auto]
                                   items-center
                                   gap-3
                                   px-4 py-4
                                   text-left
                                   sm:grid-cols-[100px_1fr_150px]
                                   sm:px-6
                                   ${present ? '' : 'absent'}"
                        >


                            

                            <div
                                class="hidden
                                       text-xs
                                       font-semibold
                                       text-slate-500
                                       sm:block"
                            >
                                ${escapeHtml(
                                    student.roll
                                )}
                            </div>



                            

                            <div
                                class="flex
                                       min-w-0
                                       items-center
                                       gap-3"
                            >

                                <div
                                    class="
                                        student-avatar
                                        flex h-10 w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-full
                                        text-xs
                                        font-bold
                                        ${
                                            present
                                                ? 'bg-slate-100 text-slate-600'
                                                : 'bg-red-50 text-red-600'
                                        }
                                    "
                                >
                                    ${escapeHtml(
                                        student.name
                                            .charAt(0)
                                            .toUpperCase()
                                    )}
                                </div>


                                <div
                                    class="min-w-0"
                                >

                                    <p
                                        class="truncate
                                               text-sm
                                               font-semibold"
                                    >
                                        ${escapeHtml(
                                            student.name
                                        )}
                                    </p>


                                    <p
                                        class="text-[11px]
                                               text-slate-400"
                                    >
                                        ${escapeHtml(
                                            student.roll
                                        )}
                                    </p>

                                </div>

                            </div>



                            

                            <div
                                class="flex
                                       items-center
                                       justify-end
                                       gap-3"
                            >

                                <span
                                    class="
                                        status-text
                                        hidden
                                        text-xs
                                        font-bold
                                        sm:block
                                        ${
                                            present
                                                ? 'text-emerald-600'
                                                : 'text-red-600'
                                        }
                                    "
                                >
                                    ${
                                        present
                                            ? 'Present'
                                            : 'Absent'
                                    }
                                </span>


                                <div
                                    class="
                                        status-icon
                                        flex h-9 w-9
                                        items-center
                                        justify-center
                                        rounded-full
                                        ${
                                            present
                                                ? 'bg-emerald-100 text-emerald-600'
                                                : 'bg-red-100 text-red-600'
                                        }
                                    "
                                >
                                    ${
                                        present
                                            ? '✓'
                                            : '×'
                                    }
                                </div>

                            </div>

                        </button>

                    `;

                }
            )
            .join('');


    /*
    |--------------------------------------------------------------------------
    | Attach click listeners
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.studentRow'
        )
        .forEach(
            row => {

                row.addEventListener(
                    'click',
                    () => {

                        const studentId =
                            String(
                                row.dataset.studentId
                            );


                        attendance[
                            studentId
                        ] =
                            !(
                                attendance[
                                    studentId
                                ] === true
                            );


                        renderStudents();

                        updateCounters();

                    }
                );

            }
        );

}


/*
|--------------------------------------------------------------------------
| Update counters
|--------------------------------------------------------------------------
*/

function updateCounters() {

    const course =
        getCourse(
            selectedCourseId
        );


    if (!course) {

        return;

    }


    const present =
        course.students.filter(
            student =>
                attendance[
                    student.id
                ] === true
        ).length;


    const absent =
        course.students.length -
        present;


    presentCounter.textContent =
        present;


    absentCounter.textContent =
        absent;

}


/*
|--------------------------------------------------------------------------
| Save attendance to Laravel
|--------------------------------------------------------------------------
*/

async function saveAttendance() {

    const courseId =
        Number(
            courseSelect.value
        );


    if (!courseId) {

        alert(
            'Please select a course.'
        );

        return;

    }


    if (!lectureName.value.trim()) {

        alert(
            'Please enter the lecture topic.'
        );

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Build request payload
    |--------------------------------------------------------------------------
    */

    const payload = {

        topic:
            lectureName.value.trim(),

        date:
            lectureDate.value,

        attendance:
            attendance

    };


    let url;

    let method;


    if (editing) {

        url =
            `${attendanceLectureUrl}/${editingLectureId}`;

        method =
            'PUT';

    } else {

        url =
            `${attendanceCourseUrl}/${courseId}`;

        method =
            'POST';


        payload.lecture_no =
            Number(
                lectureNumber.value
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Disable button while saving
    |--------------------------------------------------------------------------
    */

    const saveButton =
        document.getElementById(
            'saveButton'
        );


    saveButton.disabled =
        true;


    saveButton.textContent =
        editing
            ? 'Saving Changes...'
            : 'Saving Attendance...';


    try {

        const response =
            await fetch(
                url,
                {

                    method,

                    headers: {

                        'Content-Type':
                            'application/json',

                        'Accept':
                            'application/json',

                        'X-CSRF-TOKEN':
                            csrfToken

                    },

                    body:
                        JSON.stringify(
                            payload
                        )

                }
            );


        const data =
            await response.json();


        if (!response.ok) {

            throw new Error(
                data.message ??
                'Unable to save attendance.'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Load latest DB data
        |--------------------------------------------------------------------------
        */

        await loadAttendanceData();


        /*
        |--------------------------------------------------------------------------
        | Go back to course
        |--------------------------------------------------------------------------
        */

        openCourse(
            courseId
        );


        alert(
            data.message ??
            'Attendance saved successfully.'
        );


    } catch (error) {

        console.error(error);


        alert(
            error.message
        );

    } finally {

        saveButton.disabled =
            false;


        saveButton.textContent =
            editing
                ? 'Save Changes'
                : 'Save Attendance';

    }

}


/*
|--------------------------------------------------------------------------
| Course selection
|--------------------------------------------------------------------------
*/

courseSelect.addEventListener(
    'change',
    () => {

        if (editing) {

            return;

        }


        const courseId =
            Number(
                courseSelect.value
            );


        if (!courseId) {

            return;

        }


        selectedCourseId =
            courseId;


        const course =
            getCourse(
                courseId
            );


        const existingLectures =
            getCourseLectures(
                courseId
            );


        const nextLectureNumber =
            existingLectures.length
                ? Math.max(
                    ...existingLectures.map(
                        lecture =>
                            Number(
                                lecture.number
                            )
                    )
                ) + 1
                : 1;


        lectureNumber.value =
            nextLectureNumber;


        attendance = {};


        course.students.forEach(
            student => {

                attendance[
                    student.id
                ] = true;

            }
        );


        document
            .getElementById(
                'editorCourseBadge'
            )
            .textContent =
            course.code;


        renderStudents();

        updateCounters();

    }
);


/*
|--------------------------------------------------------------------------
| Search students
|--------------------------------------------------------------------------
*/

studentSearch.addEventListener(
    'input',
    renderStudents
);


/*
|--------------------------------------------------------------------------
| Back to all courses
|--------------------------------------------------------------------------
*/

document
    .getElementById(
        'courseBackButton'
    )
    .addEventListener(
        'click',
        () => {

            renderDashboard();

            showPage(
                dashboardPage
            );

        }
    );


/*
|--------------------------------------------------------------------------
| Back from editor
|--------------------------------------------------------------------------
*/

document
    .getElementById(
        'editorBackButton'
    )
    .addEventListener(
        'click',
        () => {

            renderCoursePage();

            showPage(
                coursePage
            );

        }
    );


/*
|--------------------------------------------------------------------------
| Cancel
|--------------------------------------------------------------------------
*/

document
    .getElementById(
        'cancelButton'
    )
    .addEventListener(
        'click',
        () => {

            renderCoursePage();

            showPage(
                coursePage
            );

        }
    );


/*
|--------------------------------------------------------------------------
| Add attendance
|--------------------------------------------------------------------------
*/

document
    .getElementById(
        'courseAddButton'
    )
    .addEventListener(
        'click',
        () => {

            openAdd(
                selectedCourseId
            );

        }
    );


/*
|--------------------------------------------------------------------------
| Save
|--------------------------------------------------------------------------
*/

document
    .getElementById(
        'saveButton'
    )
    .addEventListener(
        'click',
        saveAttendance
    );


/*
|--------------------------------------------------------------------------
| Mobile menu
|--------------------------------------------------------------------------
*/

document
    .getElementById(
        'mobileMenuButton'
    )
    .addEventListener(
        'click',
        () => {

            document
                .getElementById(
                    'mobileMenu'
                )
                .classList
                .toggle(
                    'hidden'
                );

        }
    );


/*
|--------------------------------------------------------------------------
| Date formatting
|--------------------------------------------------------------------------
*/

function formatDate(date) {

    if (!date) {

        return '—';

    }


    const parsed =
        new Date(date);


    if (Number.isNaN(
        parsed.getTime()
    )) {

        return date;

    }


    return parsed.toLocaleDateString(
        'en-IN',
        {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        }
    );

}


/*
|--------------------------------------------------------------------------
| Initial load
|--------------------------------------------------------------------------
*/

loadAttendanceData();

</script>


</body>

</html><?php /**PATH C:\Users\Atharv Vyas\OneDrive\Desktop\Web Dev\learn\laravel-\resources\views/attendance.blade.php ENDPATH**/ ?>