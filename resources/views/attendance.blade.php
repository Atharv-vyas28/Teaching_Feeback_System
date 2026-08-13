<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Sheet - Smart Feedback System</title>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen bg-[#f7f9fc] font-sans text-[#27303f] antialiased">
<div class="min-h-screen">

    <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-slate-900/30 lg:hidden"></div>

    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-[176px] -translate-x-full border-r border-[#dce1e8] bg-white transition-transform duration-200 lg:translate-x-0">

        <div class="flex h-[78px] items-center gap-2.5 px-3">
            <div class="grid h-7 w-7 shrink-0 place-items-center rounded-[7px] bg-[#2f6fdf] text-[17px] font-bold text-white">◇</div>
            <div>
                <div class="text-[11px] font-extrabold leading-[1.35] text-[#202937]">
                    Smart Feedback<br>System
                </div>
                <div class="text-[7px] tracking-[0.7px] text-[#a3a9b3]">IIT INDORE</div>
            </div>
        </div>

        <nav class="px-3 pt-2">
            <a href="#" class="mb-1.5 flex h-9 items-center gap-2.5 rounded-lg px-2.5 text-[10px] text-[#4770b3] hover:bg-[#eef5ff]">
                <span class="w-3.5 text-center text-[15px]">▦</span> Dashboard
            </a>
            <a href="#" class="relative mb-1.5 flex h-9 items-center gap-2.5 rounded-lg bg-[#eef5ff] px-2.5 text-[10px] font-bold text-[#2f6fdf]">
                <span class="w-3.5 text-center text-[15px]">▱</span> Messages
                <span class="ml-auto h-1 w-1 rounded-full bg-[#2f6fdf]"></span>
            </a>
            <a href="#" class="mb-1.5 flex h-9 items-center gap-2.5 rounded-lg px-2.5 text-[10px] text-[#4770b3] hover:bg-[#eef5ff]">
                <span class="w-3.5 text-center text-[15px]">◷</span> Attendance Records
            </a>
            <a href="#" class="mb-1.5 flex h-9 items-center gap-2.5 rounded-lg px-2.5 text-[10px] text-[#4770b3] hover:bg-[#eef5ff]">
                <span class="w-3.5 text-center text-[15px]">◦</span> Profile
            </a>
        </nav>
    </aside>

    <main class="min-h-screen lg:ml-[176px]">

        <header class="flex h-[52px] items-center border-b border-[#e6eaf0] bg-white px-4 sm:px-6">
            <button id="menuButton" type="button"
                class="mr-3 flex h-8 w-8 flex-col justify-center gap-1.5 rounded-md border border-[#e1e5eb] bg-white p-2 lg:hidden">
                <span class="h-px w-full bg-slate-500"></span>
                <span class="h-px w-full bg-slate-500"></span>
                <span class="h-px w-full bg-slate-500"></span>
            </button>

            <div class="hidden items-center gap-2 text-[10px] sm:flex">
                <span class="text-[#a0a8b5]">Staff</span>
                <span class="text-[15px] text-[#c5cbd4]">›</span>
                <span class="text-[#a0a8b5]">Messages</span>
                <span class="text-[15px] text-[#c5cbd4]">›</span>
                <strong class="text-[#303a4a]">Attendance Sheet CS301</strong>
            </div>

            <div class="ml-auto flex items-center gap-2.5">
                <button class="relative grid h-7 w-7 place-items-center text-[16px] text-[#8792a3]">
                    ♧
                    <span class="absolute right-1 top-1 h-1.5 w-1.5 rounded-full border border-white bg-red-500"></span>
                </button>
                <div class="hidden flex-col text-right sm:flex">
                    <strong class="text-[10px] text-[#303744]">Mr. Rajesh Kumar</strong>
                    <span class="text-[7px] text-[#9aa2af]">IC: STF202610</span>
                </div>
                <div class="grid h-8 w-8 place-items-center rounded-full border border-[#c7d8ee] bg-[#dbe8f9] text-[9px] font-extrabold text-[#285ca9]">
                    RK
                </div>
            </div>
        </header>

        <section class="mx-auto max-w-[740px] px-3 py-7 sm:px-5 sm:py-[54px]">

            <div class="mb-[18px]">
                <h1 class="text-[20px] font-bold tracking-[-0.4px] sm:text-[22px]">Attendance Sheet</h1>
                <p class="mt-1.5 text-[11px] text-[#7f8999]">
                    Manage attendance and anonymous student feedback.
                </p>
            </div>

            <section class="mb-[22px] rounded-[9px] border border-[#e6eaf0] bg-white p-3.5 shadow-[0_3px_16px_rgba(35,48,67,0.07)] sm:p-4">

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4 lg:gap-2.5">

                    <label>
                        <span class="mb-1.5 ml-px block text-[7px] font-extrabold text-[#596474]">COURSE</span>
                        <select id="courseSelect"
                            class="h-[31px] w-full rounded-md border border-[#e1e5eb] bg-white px-2.5 text-[9px] text-[#4a5565] outline-none focus:border-[#91b7f3] focus:ring-2 focus:ring-[#2f6fdf]/10">
                            <option>CS301 - Data Structures</option>
                            <option>CS302 - Algorithms</option>
                            <option>CS303 - Database Systems</option>
                        </select>
                    </label>

                    <label>
                        <span class="mb-1.5 ml-px block text-[7px] font-extrabold text-[#596474]">SUBJECT TOPIC</span>
                        <select id="topicSelect"
                            class="h-[31px] w-full rounded-md border border-[#e1e5eb] bg-white px-2.5 text-[9px] text-[#4a5565] outline-none focus:border-[#91b7f3] focus:ring-2 focus:ring-[#2f6fdf]/10">
                            <option>Binary Search Trees (BST)</option>
                            <option>Linked Lists</option>
                            <option>Stacks and Queues</option>
                            <option>Graphs</option>
                        </select>
                    </label>

                    <label>
                        <span class="mb-1.5 ml-px block text-[7px] font-extrabold text-[#596474]">DATE</span>
                        <input id="attendanceDate" type="date" value="2026-08-09"
                            class="h-[31px] w-full rounded-md border border-[#e1e5eb] bg-white px-2.5 text-[9px] text-[#4a5565] outline-none focus:border-[#91b7f3] focus:ring-2 focus:ring-[#2f6fdf]/10">
                    </label>

                    <label>
                        <span class="mb-1.5 ml-px block text-[7px] font-extrabold text-[#596474]">LECTURE NUMBER</span>
                        <select id="lectureSelect"
                            class="h-[31px] w-full rounded-md border border-[#e1e5eb] bg-white px-2.5 text-[9px] text-[#4a5565] outline-none focus:border-[#91b7f3] focus:ring-2 focus:ring-[#2f6fdf]/10">
                            <option>Lecture 12</option>
                            <option>Lecture 11</option>
                            <option>Lecture 13</option>
                        </select>
                    </label>
                </div>

                <div class="mt-[17px] flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <button id="markPresentBtn"
                            class="h-7 rounded-[5px] border border-[#e3e7ec] bg-white px-3 text-[8px] font-bold text-[#697486] hover:bg-slate-50">
                            Mark All Present
                        </button>
                        <button id="enableFeedbackBtn"
                            class="h-7 rounded-[5px] bg-[#e5faed] px-3 text-[8px] font-bold text-[#2a9f60] hover:bg-[#d8f7e5]">
                            Enable Feedback for All Present
                        </button>
                    </div>

                    <button id="saveAttendanceBtn"
                        class="h-7 rounded-[5px] bg-[#2f6fdf] px-4 text-[8px] font-bold text-white shadow-[0_3px_7px_rgba(47,111,223,0.17)] hover:bg-[#245fc8]">
                        Save Attendance
                    </button>
                </div>
            </section>

            @php
                $students = [
                    ['roll'=>'CS2024001','name'=>'Rahul Sharma','present'=>true,'feedback'=>true],
                    ['roll'=>'CS2024002','name'=>'Ananya Iyer','present'=>true,'feedback'=>true],
                    ['roll'=>'CS2024003','name'=>'Aarav Patel','present'=>false,'feedback'=>false],
                    ['roll'=>'CS2024004','name'=>'Sneha Reddy','present'=>true,'feedback'=>true],
                    ['roll'=>'CS2024005','name'=>'Vikram Malhotra','present'=>true,'feedback'=>true],
                    ['roll'=>'CS2024006','name'=>'Kabir Mehra','present'=>false,'feedback'=>false],
                    ['roll'=>'CS2024007','name'=>'Diya Sen','present'=>true,'feedback'=>true],
                    ['roll'=>'CS2024008','name'=>'Rohan Das','present'=>true,'feedback'=>true],
                ];
            @endphp

            <section class="overflow-hidden rounded-[9px] border border-[#e6eaf0] bg-white shadow-[0_3px_16px_rgba(35,48,67,0.07)]">
                <div class="overflow-x-auto">
                    <div class="min-w-[640px]">

                        <div class="grid min-h-9 grid-cols-[116px_1.25fr_.65fr_1fr] items-center bg-[#f3f6fa] px-[15px] text-[7px] font-extrabold text-[#697588]">
                            <div>ROLL NO</div>
                            <div>STUDENT NAME</div>
                            <div>ATTENDANCE</div>
                            <div>ANONYMOUS FEEDBACK</div>
                        </div>

                        <div id="studentRows">
                            @foreach ($students as $student)
                                <div data-roll="{{ $student['roll'] }}"
                                    class="student-row grid min-h-[43px] grid-cols-[116px_1.25fr_.65fr_1fr] items-center border-t border-[#edf0f3] px-[15px] text-[8px]">

                                    <div class="font-semibold text-[#667384]">{{ $student['roll'] }}</div>
                                    <div class="font-bold text-[#242d3a]">{{ $student['name'] }}</div>

                                    <div>
                                        <button type="button"
                                            data-present="{{ $student['present'] ? 'true' : 'false' }}"
                                            class="attendance-status inline-flex h-[17px] min-w-[45px] items-center justify-center rounded-[5px] px-2 text-[7px] font-bold
                                            {{ $student['present'] ? 'bg-[#e8fbef] text-[#26a75c]' : 'bg-[#ffe9e9] text-[#dc4d4d]' }}">
                                            {{ $student['present'] ? 'Present' : 'Absent' }}
                                        </button>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="button"
                                            data-enabled="{{ $student['feedback'] ? 'true' : 'false' }}"
                                            @disabled(!$student['present'])
                                            class="feedback-toggle relative h-[15px] w-7 rounded-full
                                            {{ $student['feedback'] ? 'bg-[#2f6fdf]' : 'bg-[#cdd5df]' }}
                                            {{ !$student['present'] ? 'cursor-not-allowed opacity-65' : '' }}">
                                            <span class="toggle-knob absolute top-0.5 h-[11px] w-[11px] rounded-full bg-white shadow
                                                {{ $student['feedback'] ? 'left-[15px]' : 'left-0.5' }}"></span>
                                        </button>

                                        <span class="feedback-label text-[7px]
                                            {{ $student['feedback'] ? 'text-[#2f6fdf]' : 'text-[#c2c7ce]' }}">
                                            {{ $student['feedback'] ? 'Enabled' : 'Feedback Disabled' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </main>
</div>

<div id="toast"
    class="pointer-events-none fixed bottom-5 right-5 z-[100] translate-y-2 rounded-lg bg-[#27303f] px-4 py-2.5 text-[11px] text-white opacity-0 shadow-xl transition-all duration-200">
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const menu = document.getElementById('menuButton');
    const toast = document.getElementById('toast');
    let timer;

    const showToast = (message) => {
        toast.textContent = message;
        toast.classList.remove('translate-y-2', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
        clearTimeout(timer);
        timer = setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-2', 'opacity-0');
        }, 2200);
    };

    const closeSidebar = () => {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    };

    menu?.addEventListener('click', () => {
        const open = sidebar.classList.contains('translate-x-0');
        if (open) closeSidebar();
        else {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            overlay.classList.remove('hidden');
        }
    });

    overlay?.addEventListener('click', closeSidebar);

    document.querySelectorAll('.attendance-status').forEach(button => {
        button.addEventListener('click', () => {
            const row = button.closest('.student-row');
            const toggle = row.querySelector('.feedback-toggle');
            const knob = toggle.querySelector('.toggle-knob');
            const label = row.querySelector('.feedback-label');
            const present = button.dataset.present === 'true';

            button.dataset.present = String(!present);
            button.textContent = present ? 'Absent' : 'Present';

            button.classList.toggle('bg-[#e8fbef]', !present);
            button.classList.toggle('text-[#26a75c]', !present);
            button.classList.toggle('bg-[#ffe9e9]', present);
            button.classList.toggle('text-[#dc4d4d]', present);

            if (present) {
                toggle.disabled = true;
                toggle.dataset.enabled = 'false';
                toggle.classList.remove('bg-[#2f6fdf]');
                toggle.classList.add('bg-[#cdd5df]', 'cursor-not-allowed', 'opacity-65');
                knob.classList.remove('left-[15px]');
                knob.classList.add('left-0.5');
                label.textContent = 'Feedback Disabled';
                label.classList.remove('text-[#2f6fdf]');
                label.classList.add('text-[#c2c7ce]');
            } else {
                toggle.disabled = false;
                toggle.classList.remove('cursor-not-allowed', 'opacity-65');
            }
        });
    });

    document.querySelectorAll('.feedback-toggle').forEach(toggle => {
        toggle.addEventListener('click', () => {
            if (toggle.disabled) return;

            const knob = toggle.querySelector('.toggle-knob');
            const label = toggle.closest('.student-row').querySelector('.feedback-label');
            const enabled = toggle.dataset.enabled === 'true';

            toggle.dataset.enabled = String(!enabled);
            toggle.classList.toggle('bg-[#2f6fdf]', !enabled);
            toggle.classList.toggle('bg-[#cdd5df]', enabled);
            knob.classList.toggle('left-[15px]', !enabled);
            knob.classList.toggle('left-0.5', enabled);
            label.classList.toggle('text-[#2f6fdf]', !enabled);
            label.classList.toggle('text-[#c2c7ce]', enabled);
            label.textContent = !enabled ? 'Enabled' : 'Disabled';
        });
    });

    document.getElementById('markPresentBtn')?.addEventListener('click', () => {
        document.querySelectorAll('.student-row').forEach(row => {
            const button = row.querySelector('.attendance-status');
            const toggle = row.querySelector('.feedback-toggle');

            button.dataset.present = 'true';
            button.textContent = 'Present';
            button.classList.remove('bg-[#ffe9e9]', 'text-[#dc4d4d]');
            button.classList.add('bg-[#e8fbef]', 'text-[#26a75c]');

            toggle.disabled = false;
            toggle.classList.remove('cursor-not-allowed', 'opacity-65');
        });
        showToast('All students marked present');
    });

    document.getElementById('enableFeedbackBtn')?.addEventListener('click', () => {
        document.querySelectorAll('.student-row').forEach(row => {
            const attendance = row.querySelector('.attendance-status');
            const toggle = row.querySelector('.feedback-toggle');
            const knob = toggle.querySelector('.toggle-knob');
            const label = row.querySelector('.feedback-label');

            if (attendance.dataset.present === 'true') {
                toggle.disabled = false;
                toggle.dataset.enabled = 'true';
                toggle.classList.remove('bg-[#cdd5df]', 'cursor-not-allowed', 'opacity-65');
                toggle.classList.add('bg-[#2f6fdf]');
                knob.classList.remove('left-0.5');
                knob.classList.add('left-[15px]');
                label.textContent = 'Enabled';
                label.classList.remove('text-[#c2c7ce]');
                label.classList.add('text-[#2f6fdf]');
            }
        });
        showToast('Feedback enabled for all present students');
    });

    document.getElementById('saveAttendanceBtn')?.addEventListener('click', () => {
        const students = [...document.querySelectorAll('.student-row')].map(row => ({
            roll: row.dataset.roll,
            present: row.querySelector('.attendance-status').dataset.present === 'true',
            feedbackEnabled: row.querySelector('.feedback-toggle').dataset.enabled === 'true'
        }));

        console.log({
            course: document.getElementById('courseSelect').value,
            topic: document.getElementById('topicSelect').value,
            date: document.getElementById('attendanceDate').value,
            lecture: document.getElementById('lectureSelect').value,
            students
        });

        showToast('Attendance saved successfully');
    });
});
</script>
</body>
</html>