<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IIT Indore | Faculty Attendance</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    </style>
</head>

<body class="bg-slate-50 text-slate-900">

    <!-- ========================================================= -->
    <!-- MOBILE HEADER -->
    <!-- ========================================================= -->

    <header
        class="sticky top-0 z-50 flex h-16 items-center justify-between
               border-b border-slate-200 bg-white px-4 lg:hidden"
    >

        <div class="flex items-center gap-3">

            <div
                class="flex h-9 w-9 items-center justify-center
                       rounded-lg bg-[#8f1d2c]
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
            class="flex h-9 w-9 items-center justify-center
                   rounded-lg border border-slate-200"
        >
            ☰
        </button>

    </header>


    <!-- ========================================================= -->
    <!-- MOBILE MENU -->
    <!-- ========================================================= -->

    <div
        id="mobileMenu"
        class="fixed inset-x-0 top-16 z-40 hidden
               border-b border-slate-200 bg-white
               p-4 shadow-lg lg:hidden"
    >

        <div class="space-y-1">

            <button
                class="w-full rounded-xl px-4 py-3 text-left
                       text-sm text-slate-500 hover:bg-slate-50"
            >
                Dashboard
            </button>

            <button
                class="w-full rounded-xl
                       bg-[#8f1d2c]/10 px-4 py-3
                       text-left text-sm font-semibold
                       text-[#8f1d2c]"
            >
                Attendance
            </button>

            <button
                class="w-full rounded-xl px-4 py-3 text-left
                       text-sm text-slate-500 hover:bg-slate-50"
            >
                Students
            </button>

            <button
                class="w-full rounded-xl px-4 py-3 text-left
                       text-sm text-slate-500 hover:bg-slate-50"
            >
                Reports
            </button>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- DESKTOP SIDEBAR -->
    <!-- ========================================================= -->

    <aside
        class="fixed inset-y-0 left-0 z-40 hidden w-64
               border-r border-slate-200 bg-white lg:block"
    >

        <div class="flex h-full flex-col">

            <div
                class="flex h-20 items-center gap-3
                       border-b border-slate-100 px-6"
            >

                <div
                    class="flex h-11 w-11 items-center
                           justify-center rounded-xl
                           bg-[#8f1d2c]
                           text-sm font-bold text-white"
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
                    class="mb-3 px-3 text-[10px]
                           font-bold uppercase
                           tracking-wider text-slate-400"
                >
                    Main Menu
                </p>

                <button
                    class="mb-1 w-full rounded-xl px-3 py-3
                           text-left text-sm text-slate-500
                           hover:bg-slate-50"
                >
                    Dashboard
                </button>

                <button
                    class="mb-1 w-full rounded-xl px-3 py-3
                           text-left text-sm text-slate-500
                           hover:bg-slate-50"
                >
                    My Courses
                </button>

                <button
                    class="mb-1 w-full rounded-xl
                           bg-[#8f1d2c]/10 px-3 py-3
                           text-left text-sm font-semibold
                           text-[#8f1d2c]"
                >
                    Attendance
                </button>

                <button
                    class="mb-1 w-full rounded-xl px-3 py-3
                           text-left text-sm text-slate-500
                           hover:bg-slate-50"
                >
                    Students
                </button>

                <button
                    class="w-full rounded-xl px-3 py-3
                           text-left text-sm text-slate-500
                           hover:bg-slate-50"
                >
                    Reports
                </button>

            </nav>


            <div class="border-t border-slate-100 p-4">

                <div
                    class="flex items-center gap-3
                           rounded-xl bg-slate-50 p-3"
                >

                    <div
                        class="flex h-10 w-10 items-center
                               justify-center rounded-full
                               bg-[#8f1d2c]
                               text-sm font-bold text-white"
                    >
                        AS
                    </div>

                    <div>

                        <p class="text-sm font-semibold">
                            Prof. Arjun Sharma
                        </p>

                        <p class="text-xs text-slate-400">
                            Computer Science
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </aside>


    <!-- ========================================================= -->
    <!-- MAIN -->
    <!-- ========================================================= -->

    <div class="lg:pl-64">

        <header
            class="hidden h-20 items-center justify-between
                   border-b border-slate-200
                   bg-white px-8 lg:flex"
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
                class="flex h-10 w-10 items-center
                       justify-center rounded-full
                       bg-[#8f1d2c]
                       text-sm font-bold text-white"
            >
                AS
            </div>

        </header>


        <main
            class="mx-auto max-w-7xl
                   px-4 py-6 sm:px-6
                   lg:px-8 lg:py-8"
        >


            <!-- ================================================= -->
            <!-- PAGE 1 : OVERALL ATTENDANCE -->
            <!-- ================================================= -->

            <section
                id="dashboardPage"
                class="page active"
            >

                <div class="mb-8">

                    <p
                        class="mb-2 text-xs font-semibold
                               uppercase tracking-wider
                               text-[#8f1d2c]"
                    >
                        Faculty Attendance
                    </p>

                    <h1
                        class="text-2xl font-bold
                               tracking-tight sm:text-3xl"
                    >
                        Attendance Overview
                    </h1>

                    <p
                        class="mt-2 text-sm text-slate-500"
                    >
                        Monitor attendance across all the courses
                        you teach.
                    </p>

                </div>


                <!-- ================================================= -->
                <!-- OVERALL GRAPH -->
                <!-- ================================================= -->

                <div
                    class="mb-8 grid gap-5
                           lg:grid-cols-[1.1fr_0.9fr]"
                >

                    <!-- Overall percentage -->

                    <div
                        class="rounded-2xl border
                               border-slate-200
                               bg-white p-6
                               shadow-sm sm:p-8"
                    >

                        <div
                            class="flex flex-col
                                   items-center
                                   justify-center
                                   gap-8 sm:flex-row"
                        >

                            <!-- Donut -->

                            <div class="relative h-44 w-44">

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
                                    class="absolute inset-0
                                           flex flex-col
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


                            <!-- Stats -->

                            <div class="w-full sm:w-auto">

                                <p
                                    class="text-xs
                                           text-slate-400"
                                >
                                    Overall Attendance
                                </p>

                                <h2
                                    class="mt-1 text-xl
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
                                            class="mt-1 text-xl
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
                                            class="mt-1 text-xl
                                                   font-bold"
                                        >
                                            0
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Attendance distribution -->

                    <div
                        class="rounded-2xl border
                               border-slate-200
                               bg-white p-6
                               shadow-sm sm:p-8"
                    >

                        <div class="mb-5">

                            <h2 class="font-bold">
                                Attendance Distribution
                            </h2>

                            <p
                                class="mt-1 text-xs
                                       text-slate-400"
                            >
                                Overall student attendance
                            </p>

                        </div>


                        <div class="space-y-5">

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
                                    class="h-3 overflow-hidden
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
                                    class="h-3 overflow-hidden
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


                <!-- ================================================= -->
                <!-- COURSES -->
                <!-- ================================================= -->

                <div class="mb-8">

                    <div class="mb-5">

                        <h2 class="text-lg font-bold">
                            Courses You Teach
                        </h2>

                        <p
                            class="mt-1 text-xs
                                   text-slate-400"
                        >
                            Select a course to view detailed
                            attendance.
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


            <!-- ================================================= -->
            <!-- PAGE 2 : COURSE DETAILS -->
            <!-- ================================================= -->

            <section
                id="coursePage"
                class="page"
            >

                <button
                    id="courseBackButton"
                    class="mb-6 flex items-center gap-2
                           text-sm font-medium
                           text-slate-500
                           hover:text-[#8f1d2c]"
                >
                    ← All Courses
                </button>


                <!-- Course heading -->

                <div
                    class="mb-7 flex flex-col
                           gap-5
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
                        class="flex items-center
                               justify-center gap-2
                               rounded-xl
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


                <!-- Course Graph -->

                <div
                    class="mb-7 grid gap-5
                           lg:grid-cols-[0.9fr_1.1fr]"
                >

                    <!-- Donut -->

                    <div
                        class="rounded-2xl
                               border border-slate-200
                               bg-white p-6
                               shadow-sm"
                    >

                        <div
                            class="flex flex-col
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
                                    class="absolute inset-0
                                           flex flex-col
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
                                0 students present
                            </p>

                        </div>

                    </div>


                    <!-- Lecture-wise graph -->

                    <div
                        class="rounded-2xl
                               border border-slate-200
                               bg-white p-6
                               shadow-sm"
                    >

                        <div class="mb-6">

                            <h2 class="font-bold">
                                Lecture-wise Attendance
                            </h2>

                            <p
                                class="mt-1 text-xs
                                       text-slate-400"
                            >
                                Attendance percentage for each lecture
                            </p>

                        </div>


                        <div
                            id="lectureGraph"
                            class="space-y-5"
                        >
                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- PREVIOUS ATTENDANCE -->
                <!-- ================================================= -->

                <div
                    class="overflow-hidden rounded-2xl
                           border border-slate-200
                           bg-white shadow-sm"
                >

                    <div
                        class="border-b border-slate-200
                               px-5 py-5 sm:px-6"
                    >

                        <h2 class="font-bold">
                            Previous Attendance
                        </h2>

                        <p
                            class="mt-1 text-xs
                                   text-slate-400"
                        >
                            Click edit to view or modify student
                            attendance.
                        </p>

                    </div>


                    <div
                        id="courseLectureTable"
                        class="hidden md:block"
                    >
                    </div>


                    <div
                        id="courseLectureMobile"
                        class="divide-y divide-slate-100
                               md:hidden"
                    >
                    </div>

                </div>

            </section>


            <!-- ================================================= -->
            <!-- PAGE 3 : ATTENDANCE EDITOR -->
            <!-- ================================================= -->

            <section
                id="editorPage"
                class="page"
            >

                <button
                    id="editorBackButton"
                    class="mb-6 flex items-center gap-2
                           text-sm font-medium
                           text-slate-500
                           hover:text-[#8f1d2c]"
                >
                    ← Back to Course
                </button>


                <div
                    class="mb-6 flex flex-col gap-5
                           lg:flex-row lg:items-end
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
                                   font-bold sm:text-3xl"
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


                    <div class="grid grid-cols-2 gap-3">

                        <div
                            class="rounded-xl border
                                   border-emerald-100
                                   bg-emerald-50
                                   px-5 py-3 text-center"
                        >

                            <p
                                class="text-[11px]
                                       text-emerald-600"
                            >
                                Present
                            </p>

                            <p
                                id="presentCounter"
                                class="mt-1 text-xl
                                       font-bold
                                       text-emerald-700"
                            >
                                0
                            </p>

                        </div>


                        <div
                            class="rounded-xl border
                                   border-red-100
                                   bg-red-50
                                   px-5 py-3 text-center"
                        >

                            <p
                                class="text-[11px]
                                       text-red-600"
                            >
                                Absent
                            </p>

                            <p
                                id="absentCounter"
                                class="mt-1 text-xl
                                       font-bold
                                       text-red-700"
                            >
                                0
                            </p>

                        </div>

                    </div>

                </div>


                <!-- Lecture Details -->

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
                        class="mt-5 grid gap-5
                               md:grid-cols-2
                               lg:grid-cols-4"
                    >

                        <div class="lg:col-span-2">

                            <label
                                class="mb-2 block text-xs
                                       font-bold text-slate-600"
                            >
                                Course
                            </label>

                            <select
                                id="courseSelect"
                                class="w-full rounded-xl
                                       border border-slate-200
                                       bg-white px-4 py-3
                                       text-sm outline-none
                                       focus:border-[#8f1d2c]"
                            >

                                <option value="">
                                    Select Course
                                </option>

                            </select>

                        </div>


                        <div>

                            <label
                                class="mb-2 block text-xs
                                       font-bold text-slate-600"
                            >
                                Lecture Number
                            </label>

                            <input
                                id="lectureNumber"
                                type="number"
                                class="w-full rounded-xl
                                       border border-slate-200
                                       bg-slate-50 px-4 py-3
                                       text-sm font-semibold
                                       outline-none"
                            >

                        </div>


                        <div>

                            <label
                                class="mb-2 block text-xs
                                       font-bold text-slate-600"
                            >
                                Date
                            </label>

                            <input
                                id="lectureDate"
                                type="date"
                                class="w-full rounded-xl
                                       border border-slate-200
                                       px-4 py-3 text-sm
                                       outline-none
                                       focus:border-[#8f1d2c]"
                            >

                        </div>


                        <div
                            class="md:col-span-2
                                   lg:col-span-4"
                        >

                            <label
                                class="mb-2 block text-xs
                                       font-bold text-slate-600"
                            >
                                Lecture Name / Topic
                            </label>

                            <input
                                id="lectureName"
                                type="text"
                                placeholder="Example: Binary Search Trees"
                                class="w-full rounded-xl
                                       border border-slate-200
                                       px-4 py-3 text-sm
                                       outline-none
                                       focus:border-[#8f1d2c]"
                            >

                        </div>

                    </div>

                </div>


                <!-- Students -->

                <div
                    class="overflow-hidden rounded-2xl
                           border border-slate-200
                           bg-white shadow-sm"
                >

                    <div
                        class="flex flex-col gap-4
                               border-b border-slate-200
                               p-5 sm:p-6
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
                                Click a student to mark absent.
                            </p>

                        </div>


                        <input
                            id="studentSearch"
                            type="text"
                            placeholder="Search student..."
                            class="w-full rounded-xl
                                   border border-slate-200
                                   px-4 py-3 text-sm
                                   outline-none
                                   focus:border-[#8f1d2c]
                                   md:w-72"
                        >

                    </div>


                    <div
                        class="flex items-center
                               justify-between
                               border-b border-slate-100
                               bg-slate-50
                               px-5 py-3
                               text-xs sm:px-6"
                    >

                        <span class="text-slate-500">
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
                        class="divide-y divide-slate-100"
                    >
                    </div>


                    <div
                        class="flex flex-col-reverse gap-3
                               border-t border-slate-200
                               bg-slate-50 p-5
                               sm:flex-row
                               sm:justify-end sm:px-6"
                    >

                        <button
                            id="cancelButton"
                            class="rounded-xl
                                   border border-slate-200
                                   bg-white px-6 py-3
                                   text-sm font-semibold
                                   text-slate-600"
                        >
                            Cancel
                        </button>


                        <button
                            id="saveButton"
                            class="rounded-xl
                                   bg-[#8f1d2c]
                                   px-6 py-3
                                   text-sm font-semibold
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

/* ============================================================= */
/* DUMMY DATA */
/* ============================================================= */

const courses = [

    {
        id: 1,
        code: "CSE 201",
        name: "Data Structures & Algorithms",

        students: [
            { id: 1, roll: "230001", name: "Aarav Sharma" },
            { id: 2, roll: "230002", name: "Aditya Patel" },
            { id: 3, roll: "230003", name: "Ananya Singh" },
            { id: 4, roll: "230004", name: "Arjun Verma" },
            { id: 5, roll: "230005", name: "Dev Mehta" },
            { id: 6, roll: "230006", name: "Dhruv Gupta" },
            { id: 7, roll: "230007", name: "Ishita Jain" },
            { id: 8, roll: "230008", name: "Karan Joshi" },
            { id: 9, roll: "230009", name: "Meera Shah" },
            { id: 10, roll: "230010", name: "Naman Agarwal" },
            { id: 11, roll: "230011", name: "Neha Kapoor" },
            { id: 12, roll: "230012", name: "Rohan Malhotra" },
            { id: 13, roll: "230013", name: "Sakshi Verma" },
            { id: 14, roll: "230014", name: "Shivam Yadav" },
            { id: 15, roll: "230015", name: "Tanvi Mishra" },
            { id: 16, roll: "230016", name: "Vedant Rao" },
            { id: 17, roll: "230017", name: "Yash Thakur" },
            { id: 18, roll: "230018", name: "Zoya Khan" }
        ]
    },

    {
        id: 2,
        code: "CSE 203",
        name: "Database Management Systems",

        students: [
            { id: 101, roll: "230021", name: "Aditi Shah" },
            { id: 102, roll: "230022", name: "Akash Jain" },
            { id: 103, roll: "230023", name: "Harsh Patel" },
            { id: 104, roll: "230024", name: "Kriti Sharma" },
            { id: 105, roll: "230025", name: "Manav Gupta" },
            { id: 106, roll: "230026", name: "Priya Singh" },
            { id: 107, roll: "230027", name: "Rahul Verma" },
            { id: 108, roll: "230028", name: "Simran Kapoor" }
        ]
    },

    {
        id: 3,
        code: "CSE 205",
        name: "Operating Systems",

        students: [
            { id: 201, roll: "230041", name: "Abhishek Rao" },
            { id: 202, roll: "230042", name: "Kunal Mehta" },
            { id: 203, roll: "230043", name: "Manya Jain" },
            { id: 204, roll: "230044", name: "Riya Sharma" },
            { id: 205, roll: "230045", name: "Vivek Patel" }
        ]
    }

];


/* ============================================================= */
/* PREVIOUS LECTURES */
/* ============================================================= */

let lectures = [

    {
        id: 1,
        courseId: 1,
        courseCode: "CSE 201",
        number: 13,
        name: "Trees & Graphs",
        date: "21 Aug 2026",

        attendance: {
            1:true, 2:true, 3:false, 4:true,
            5:true, 6:true, 7:true, 8:true,
            9:true, 10:true, 11:true, 12:true,
            13:true, 14:false, 15:true, 16:true,
            17:true, 18:true
        }
    },

    {
        id: 2,
        courseId: 1,
        courseCode: "CSE 201",
        number: 12,
        name: "Binary Search Trees",
        date: "19 Aug 2026",

        attendance: {
            1:true, 2:true, 3:true, 4:true,
            5:true, 6:true, 7:false, 8:true,
            9:true, 10:true, 11:true, 12:true,
            13:true, 14:true, 15:true, 16:true,
            17:true, 18:false
        }
    },

    {
        id: 3,
        courseId: 1,
        courseCode: "CSE 201",
        number: 11,
        name: "Stacks & Queues",
        date: "17 Aug 2026",

        attendance: {
            1:true, 2:true, 3:true, 4:true,
            5:true, 6:true, 7:true, 8:true,
            9:true, 10:true, 11:true, 12:true,
            13:true, 14:true, 15:false, 16:true,
            17:true, 18:true
        }
    },

    {
        id: 4,
        courseId: 1,
        courseCode: "CSE 201",
        number: 10,
        name: "Linked Lists",
        date: "15 Aug 2026",

        attendance: {
            1:true, 2:true, 3:true, 4:true,
            5:true, 6:true, 7:true, 8:false,
            9:true, 10:true, 11:true, 12:true,
            13:true, 14:true, 15:true, 16:true,
            17:true, 18:true
        }
    },

    {
        id: 5,
        courseId: 2,
        courseCode: "CSE 203",
        number: 8,
        name: "SQL Joins",
        date: "20 Aug 2026",

        attendance: {
            101:true, 102:true, 103:false,
            104:true, 105:true, 106:true,
            107:true, 108:true
        }
    },

    {
        id: 6,
        courseId: 2,
        courseCode: "CSE 203",
        number: 7,
        name: "Normalization",
        date: "18 Aug 2026",

        attendance: {
            101:true, 102:true, 103:true,
            104:true, 105:false, 106:true,
            107:true, 108:true
        }
    },

    {
        id: 7,
        courseId: 3,
        courseCode: "CSE 205",
        number: 6,
        name: "Process Scheduling",
        date: "21 Aug 2026",

        attendance: {
            201:true, 202:true, 203:true,
            204:false, 205:true
        }
    }

];


/* ============================================================= */
/* STATE */
/* ============================================================= */

let selectedCourseId = null;

let editingLectureId = null;

let attendance = {};

let editing = false;


/* ============================================================= */
/* ELEMENTS */
/* ============================================================= */

const dashboardPage =
    document.getElementById("dashboardPage");

const coursePage =
    document.getElementById("coursePage");

const editorPage =
    document.getElementById("editorPage");


/* ============================================================= */
/* LOCAL STORAGE */
/* ============================================================= */

const saved =
    localStorage.getItem("iit-attendance");

if (saved) {

    try {
        lectures = JSON.parse(saved);
    } catch(e) {}

}


function persist() {

    localStorage.setItem(
        "iit-attendance",
        JSON.stringify(lectures)
    );

}


/* ============================================================= */
/* COURSE */
/* ============================================================= */

function getCourse(id) {

    return courses.find(
        c => c.id === Number(id)
    );

}


function getCourseLectures(courseId) {

    return lectures
        .filter(
            lecture =>
                lecture.courseId === courseId
        )
        .sort(
            (a,b) =>
                b.number - a.number
        );

}


/* ============================================================= */
/* LECTURE STATS */
/* ============================================================= */

function getLectureStats(lecture) {

    const course =
        getCourse(lecture.courseId);

    const total =
        course.students.length;

    const present =
        course.students.filter(
            student =>
                lecture.attendance[
                    student.id
                ]
        ).length;

    const absent =
        total - present;

    const percentage =
        total === 0
            ? 0
            : Math.round(
                present / total * 100
            );

    return {
        total,
        present,
        absent,
        percentage
    };

}


/* ============================================================= */
/* COURSE STATS */
/* ============================================================= */

function getCourseStats(courseId) {

    const course =
        getCourse(courseId);

    const courseLectures =
        getCourseLectures(courseId);

    let present = 0;

    let possible = 0;

    courseLectures.forEach(
        lecture => {

            const stats =
                getLectureStats(lecture);

            present += stats.present;

            possible += stats.total;

        }
    );


    const percentage =
        possible === 0
            ? 0
            : Math.round(
                present / possible * 100
            );


    return {
        present,
        possible,
        percentage,
        lectures: courseLectures.length
    };

}


/* ============================================================= */
/* OVERALL STATS */
/* ============================================================= */

function getOverallStats() {

    let present = 0;

    let possible = 0;


    lectures.forEach(
        lecture => {

            const stats =
                getLectureStats(lecture);

            present += stats.present;

            possible += stats.total;

        }
    );


    const percentage =
        possible === 0
            ? 0
            : Math.round(
                present / possible * 100
            );


    return {
        present,
        possible,
        percentage,
        absent:
            possible - present
    };

}


/* ============================================================= */
/* DONUT */
/* ============================================================= */

function setDonut(element, percentage) {

    const circumference =
        2 * Math.PI * 48;

    const offset =
        circumference -
        (percentage / 100) *
        circumference;


    element.style.strokeDashoffset =
        offset;

}


/* ============================================================= */
/* DASHBOARD */
/* ============================================================= */

function renderDashboard() {

    const stats =
        getOverallStats();


    document
        .getElementById("overallPercentage")
        .textContent =
        `${stats.percentage}%`;


    setDonut(
        document.getElementById(
            "overallDonut"
        ),
        stats.percentage
    );


    document
        .getElementById("totalLectures")
        .textContent =
        lectures.length;


    document
        .getElementById("totalCourses")
        .textContent =
        courses.length;


    document
        .getElementById("overallPresentText")
        .textContent =
        stats.present;


    document
        .getElementById("overallAbsentText")
        .textContent =
        stats.absent;


    const total =
        stats.possible || 1;


    document
        .getElementById("overallPresentBar")
        .style.width =
        `${stats.present / total * 100}%`;


    document
        .getElementById("overallAbsentBar")
        .style.width =
        `${stats.absent / total * 100}%`;


    renderCourses();

}


/* ============================================================= */
/* COURSE CARDS */
/* ============================================================= */

function renderCourses() {

    const container =
        document.getElementById(
            "courseContainer"
        );

    container.innerHTML = "";


    courses.forEach(course => {

        const stats =
            getCourseStats(course.id);


        const card =
            document.createElement("button");

        card.className =
            "group rounded-2xl border " +
            "border-slate-200 bg-white p-5 " +
            "text-left shadow-sm transition " +
            "hover:-translate-y-1 hover:border-[#8f1d2c]/30 " +
            "hover:shadow-lg";


        card.innerHTML = `

            <div class="flex items-start
                        justify-between">

                <div>

                    <span
                        class="rounded-lg
                               bg-[#8f1d2c]/10
                               px-2.5 py-1
                               text-[11px]
                               font-bold
                               text-[#8f1d2c]"
                    >
                        ${course.code}
                    </span>

                    <h3
                        class="mt-3 font-bold"
                    >
                        ${course.name}
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
                        ${stats.lectures} lectures
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
                            width:${stats.percentage}%
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
                    ${course.students.length}
                    students
                </span>

                <span
                    class="text-xs
                           font-bold
                           text-[#8f1d2c]
                           transition
                           group-hover:translate-x-1"
                >
                    View Course →
                </span>

            </div>

        `;


        card.addEventListener(
            "click",
            () =>
                openCourse(
                    course.id
                )
        );


        container.appendChild(card);

    });

}


/* ============================================================= */
/* OPEN COURSE */
/* ============================================================= */

function openCourse(courseId) {

    selectedCourseId =
        courseId;


    const course =
        getCourse(courseId);


    document
        .getElementById("coursePageCode")
        .textContent =
        course.code;


    document
        .getElementById("coursePageName")
        .textContent =
        course.name;


    dashboardPage
        .classList
        .remove("active");


    editorPage
        .classList
        .remove("active");


    coursePage
        .classList
        .add("active");


    renderCoursePage();


    window.scrollTo({
        top:0,
        behavior:"smooth"
    });

}


/* ============================================================= */
/* COURSE PAGE */
/* ============================================================= */

function renderCoursePage() {

    const stats =
        getCourseStats(
            selectedCourseId
        );


    document
        .getElementById("coursePercentage")
        .textContent =
        `${stats.percentage}%`;


    setDonut(
        document.getElementById(
            "courseDonut"
        ),
        stats.percentage
    );


    document
        .getElementById(
            "courseAttendanceLabel"
        )
        .textContent =
        `${stats.present} present records out of ${stats.possible}`;


    renderLectureGraph();

    renderCourseLectures();

}


/* ============================================================= */
/* LECTURE GRAPH */
/* ============================================================= */

function renderLectureGraph() {

    const container =
        document.getElementById(
            "lectureGraph"
        );


    const courseLectures =
        getCourseLectures(
            selectedCourseId
        );


    if (!courseLectures.length) {

        container.innerHTML = `
            <p class="text-sm text-slate-400">
                No lectures recorded yet.
            </p>
        `;

        return;

    }


    container.innerHTML =
        courseLectures
            .slice(0,8)
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
                                        ${lecture.name}
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
                                               ? "bg-emerald-500"
                                               : "bg-red-400"
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
            .join("");

}


/* ============================================================= */
/* COURSE LECTURES TABLE */
/* ============================================================= */

function renderCourseLectures() {

    const desktop =
        document.getElementById(
            "courseLectureTable"
        );

    const mobile =
        document.getElementById(
            "courseLectureMobile"
        );


    const courseLectures =
        getCourseLectures(
            selectedCourseId
        );


    desktop.innerHTML = `
        <table class="w-full">

            <thead>

                <tr
                    class="border-b border-slate-100
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
                    courseLectures.map(
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
                                        ${lecture.name}
                                    </td>


                                    <td
                                        class="px-6 py-5
                                               text-sm
                                               text-slate-500"
                                    >
                                        ${lecture.date}
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
                                                               ? "bg-emerald-500"
                                                               : "bg-red-400"
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
                                            data-edit="${lecture.id}"
                                            class="editCourseLecture
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
                    ).join("")
                }

            </tbody>

        </table>
    `;


    mobile.innerHTML =
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
                                        ${lecture.name}
                                    </h3>

                                    <p
                                        class="mt-1
                                               text-xs
                                               text-slate-400"
                                    >
                                        ${lecture.date}
                                    </p>

                                </div>


                                <button
                                    data-edit="${lecture.id}"
                                    class="editCourseLecture
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
                                    class="h-2 overflow-hidden
                                           rounded-full
                                           bg-slate-100"
                                >

                                    <div
                                        class="h-full
                                               rounded-full
                                               ${
                                                   stats.percentage >= 75
                                                   ? "bg-emerald-500"
                                                   : "bg-red-400"
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
            .join("");


    document
        .querySelectorAll(
            ".editCourseLecture"
        )
        .forEach(button => {

            button.addEventListener(
                "click",
                () => {

                    openEdit(
                        Number(
                            button.dataset.edit
                        )
                    );

                }
            );

        });

}


/* ============================================================= */
/* EDITOR ELEMENTS */
/* ============================================================= */

const courseSelect =
    document.getElementById(
        "courseSelect"
    );

const lectureNumber =
    document.getElementById(
        "lectureNumber"
    );

const lectureName =
    document.getElementById(
        "lectureName"
    );

const lectureDate =
    document.getElementById(
        "lectureDate"
    );

const studentSearch =
    document.getElementById(
        "studentSearch"
    );

const studentContainer =
    document.getElementById(
        "studentContainer"
    );


/* ============================================================= */
/* COURSE SELECT OPTIONS */
/* ============================================================= */

courses.forEach(course => {

    const option =
        document.createElement(
            "option"
        );

    option.value =
        course.id;

    option.textContent =
        `${course.code} — ${course.name}`;

    courseSelect.appendChild(
        option
    );

});


/* ============================================================= */
/* OPEN ADD */
/* ============================================================= */

function openAdd(courseId) {

    editing = false;

    editingLectureId = null;

    selectedCourseId =
        courseId;


    const course =
        getCourse(courseId);


    const nextLecture =
        getCourseLectures(
            courseId
        ).length + 1;


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
            "editorTitle"
        )
        .textContent =
        "Add Attendance";


    document
        .getElementById(
            "editorCourseBadge"
        )
        .textContent =
        course.code;


    courseSelect.value =
        courseId;


    courseSelect.disabled =
        true;


    lectureNumber.value =
        nextLecture;


    lectureNumber.disabled =
        true;


    lectureName.value =
        "";


    lectureDate.value =
        new Date()
            .toISOString()
            .split("T")[0];


    studentSearch.value = "";


    coursePage
        .classList
        .remove("active");


    editorPage
        .classList
        .add("active");


    renderStudents();

    updateCounters();


    window.scrollTo({
        top:0,
        behavior:"smooth"
    });

}


/* ============================================================= */
/* OPEN EDIT */
/* ============================================================= */

function openEdit(lectureId) {

    const lecture =
        lectures.find(
            l =>
                l.id === lectureId
        );


    if (!lecture) return;


    editing = true;

    editingLectureId =
        lectureId;

    selectedCourseId =
        lecture.courseId;


    const course =
        getCourse(
            lecture.courseId
        );


    attendance =
        JSON.parse(
            JSON.stringify(
                lecture.attendance
            )
        );


    document
        .getElementById(
            "editorTitle"
        )
        .textContent =
        "Edit Attendance";


    document
        .getElementById(
            "editorCourseBadge"
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
        convertDate(
            lecture.date
        );


    studentSearch.value = "";


    coursePage
        .classList
        .remove("active");


    editorPage
        .classList
        .add("active");


    renderStudents();

    updateCounters();


    window.scrollTo({
        top:0,
        behavior:"smooth"
    });

}


/* ============================================================= */
/* STUDENTS */
/* ============================================================= */

function renderStudents() {

    const course =
        getCourse(
            selectedCourseId
        );


    const search =
        studentSearch.value
            .toLowerCase()
            .trim();


    const students =
        course.students.filter(
            student =>

                student.name
                    .toLowerCase()
                    .includes(search)

                ||

                student.roll
                    .includes(search)
        );


    document
        .getElementById(
            "studentCount"
        )
        .textContent =
        course.students.length;


    studentContainer.innerHTML =
        students.map(
            student => {

                const present =
                    !!attendance[
                        student.id
                    ];


                return `

                    <button
                        data-student="${student.id}"
                        class="studentRow grid
                               w-full
                               grid-cols-[1fr_auto]
                               items-center
                               gap-3 px-4 py-4
                               text-left
                               hover:bg-slate-50
                               sm:grid-cols-[100px_1fr_150px]
                               sm:px-6"
                    >

                        <div
                            class="hidden
                                   text-xs
                                   font-semibold
                                   text-slate-500
                                   sm:block"
                        >
                            ${student.roll}
                        </div>


                        <div
                            class="flex
                                   min-w-0
                                   items-center
                                   gap-3"
                        >

                            <div
                                class="
                                flex h-10 w-10
                                shrink-0
                                items-center
                                justify-center
                                rounded-full
                                text-xs font-bold
                                ${
                                    present
                                    ? "bg-slate-100 text-slate-600"
                                    : "bg-red-50 text-red-600"
                                }
                                "
                            >
                                ${student.name[0]}
                            </div>


                            <div class="min-w-0">

                                <p
                                    class="truncate
                                           text-sm
                                           font-semibold"
                                >
                                    ${student.name}
                                </p>

                                <p
                                    class="text-[11px]
                                           text-slate-400"
                                >
                                    ${student.roll}
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
                                class="hidden
                                       text-xs
                                       font-bold
                                       sm:block
                                       ${
                                           present
                                           ? "text-emerald-600"
                                           : "text-red-600"
                                       }"
                            >
                                ${
                                    present
                                    ? "Present"
                                    : "Absent"
                                }
                            </span>


                            <div
                                class="
                                flex h-9 w-9
                                items-center
                                justify-center
                                rounded-full
                                ${
                                    present
                                    ? "bg-emerald-100 text-emerald-600"
                                    : "bg-red-100 text-red-600"
                                }
                                "
                            >

                                <span
                                    class="font-bold"
                                >
                                    ${
                                        present
                                        ? "✓"
                                        : "×"
                                    }
                                </span>

                            </div>

                        </div>

                    </button>

                `;

            }
        )
        .join("");


    document
        .querySelectorAll(
            ".studentRow"
        )
        .forEach(row => {

            row.addEventListener(
                "click",
                () => {

                    const id =
                        Number(
                            row.dataset.student
                        );


                    attendance[id] =
                        !attendance[id];


                    renderStudents();

                    updateCounters();

                }
            );

        });

}


/* ============================================================= */
/* COUNTERS */
/* ============================================================= */

function updateCounters() {

    const course =
        getCourse(
            selectedCourseId
        );


    const present =
        course.students.filter(
            student =>
                attendance[
                    student.id
                ]
        ).length;


    const absent =
        course.students.length -
        present;


    document
        .getElementById(
            "presentCounter"
        )
        .textContent =
        present;


    document
        .getElementById(
            "absentCounter"
        )
        .textContent =
        absent;

}


/* ============================================================= */
/* SAVE */
/* ============================================================= */

document
    .getElementById(
        "saveButton"
    )
    .addEventListener(
        "click",
        () => {

            if (!lectureName.value.trim()) {

                alert(
                    "Please enter the lecture topic."
                );

                return;

            }


            const courseId =
                Number(
                    courseSelect.value
                );


            const course =
                getCourse(courseId);


            if (editing) {

                const lecture =
                    lectures.find(
                        l =>
                            l.id ===
                            editingLectureId
                    );


                lecture.name =
                    lectureName.value.trim();

                lecture.date =
                    lectureDate.value;

                lecture.attendance =
                    JSON.parse(
                        JSON.stringify(
                            attendance
                        )
                    );

            } else {

                lectures.push({

                    id:
                        Date.now(),

                    courseId,

                    courseCode:
                        course.code,

                    number:
                        Number(
                            lectureNumber.value
                        ),

                    name:
                        lectureName.value.trim(),

                    date:
                        lectureDate.value,

                    attendance:
                        JSON.parse(
                            JSON.stringify(
                                attendance
                            )
                        )

                });

            }


            persist();


            alert(
                editing
                    ? "Attendance updated successfully."
                    : "Attendance saved successfully."
            );


            openCourse(
                courseId
            );

        }
    );


/* ============================================================= */
/* SEARCH */
/* ============================================================= */

studentSearch.addEventListener(
    "input",
    renderStudents
);


/* ============================================================= */
/* BACK BUTTONS */
/* ============================================================= */

document
    .getElementById(
        "courseBackButton"
    )
    .addEventListener(
        "click",
        () => {

            coursePage
                .classList
                .remove("active");

            dashboardPage
                .classList
                .add("active");


            renderDashboard();


            window.scrollTo({
                top:0,
                behavior:"smooth"
            });

        }
    );


document
    .getElementById(
        "editorBackButton"
    )
    .addEventListener(
        "click",
        () => {

            editorPage
                .classList
                .remove("active");

            coursePage
                .classList
                .add("active");


            renderCoursePage();


            window.scrollTo({
                top:0,
                behavior:"smooth"
            });

        }
    );


document
    .getElementById(
        "cancelButton"
    )
    .addEventListener(
        "click",
        () => {

            editorPage
                .classList
                .remove("active");

            coursePage
                .classList
                .add("active");


            renderCoursePage();

        }
    );


/* ============================================================= */
/* ADD ATTENDANCE FROM COURSE PAGE */
/* ============================================================= */

document
    .getElementById(
        "courseAddButton"
    )
    .addEventListener(
        "click",
        () => {

            openAdd(
                selectedCourseId
            );

        }
    );


/* ============================================================= */
/* MOBILE MENU */
/* ============================================================= */

document
    .getElementById(
        "mobileMenuButton"
    )
    .addEventListener(
        "click",
        () => {

            document
                .getElementById(
                    "mobileMenu"
                )
                .classList
                .toggle("hidden");

        }
    );


/* ============================================================= */
/* DATE */
/* ============================================================= */

function convertDate(date) {

    const d =
        new Date(date);


    if (isNaN(d)) {

        return new Date()
            .toISOString()
            .split("T")[0];

    }


    return d
        .toISOString()
        .split("T")[0];

}


/* ============================================================= */
/* INITIALIZE */
/* ============================================================= */

renderDashboard();

</script>

</body>
</html>