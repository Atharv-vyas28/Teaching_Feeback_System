<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Portal | Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100">

    <div class="min-h-screen flex flex-col">

        {{-- ================= HEADER ================= --}}
        <header class="bg-white border-b border-slate-200">

            <div class="max-w-7xl mx-auto px-6 lg:px-10">

                <div class="h-20 flex items-center justify-between">

                    {{-- College Logo --}}
                    <div class="flex items-center gap-4">

                        <div
                            class="w-12 h-12 rounded-full
                                   bg-[#0b2a4a]
                                   flex items-center justify-center
                                   text-white font-bold text-lg
                                   border-4 border-[#d4af37]"
                        >
                            C
                        </div>

                        <div>

                            <h1 class="text-lg md:text-xl font-bold
                                       text-[#0b2a4a] tracking-wide">
                                IIT Indore
                            </h1>

                            <p class="text-xs text-slate-500 tracking-wide">
                                Excellence • Knowledge • Character
                            </p>

                        </div>

                    </div>


                    {{-- Website --}}
                    <div class="hidden sm:block">

                        <a
                            href="/"
                            class="text-sm font-medium
                                   text-slate-600
                                   hover:text-[#0b2a4a]
                                   transition"
                        >
                            IITI Website
                        </a>

                    </div>

                </div>

            </div>

        </header>


        {{-- ================= MAIN ================= --}}
        <main
            class="flex-1 relative flex items-center
                   justify-center px-5 py-12"
        >

            {{-- Background decoration --}}
            <div
                class="absolute inset-0 overflow-hidden
                       pointer-events-none"
            >

                <div
                    class="absolute -top-32 -left-32
                           w-96 h-96 rounded-full
                           bg-[#0b2a4a]/5"
                ></div>

                <div
                    class="absolute -bottom-40 -right-40
                           w-[500px] h-[500px]
                           rounded-full
                           bg-[#d4af37]/10"
                ></div>

            </div>


            <div class="relative w-full max-w-md">


                {{-- Login Card --}}
                <div
                    class="bg-white rounded-2xl
                           border border-slate-200
                           shadow-xl shadow-slate-900/5
                           overflow-hidden"
                >

                    {{-- Card Header --}}
                    <div
                        class="bg-[#0b2a4a]
                               px-8 py-7
                               text-center"
                    >

                        <div
                            class="mx-auto mb-4
                                   w-14 h-14
                                   rounded-full
                                   bg-white
                                   flex items-center justify-center
                                   text-[#0b2a4a]
                                   text-2xl font-bold
                                   border-4 border-[#d4af37]"
                        >
                            C
                        </div>

                        <h2 class="text-2xl font-bold text-white">
                            Student Portal
                        </h2>

                        <p class="mt-1 text-sm text-white/70">
                            Sign in to access your account
                        </p>

                    </div>


                    {{-- Form --}}
                    <div class="p-8">

                        {{-- Errors --}}
                        @if ($errors->any())

                            <div
                                class="mb-6 rounded-lg
                                       border border-red-200
                                       bg-red-50
                                       px-4 py-3"
                            >

                                <ul
                                    class="text-sm text-red-600
                                           space-y-1"
                                >

                                    @foreach ($errors->all() as $error)

                                        <li>
                                            {{ $error }}
                                        </li>

                                    @endforeach

                                </ul>

                            </div>

                        @endif


                        <form
                            action="{{ route('login') }}"
                            method="POST"
                            class="space-y-5"
                        >

                            @csrf


                            {{-- Email --}}
                            <div>

                                <label
                                    for="email"
                                    class="block mb-2
                                           text-sm font-semibold
                                           text-slate-700"
                                >
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="Enter your email address"
                                    required
                                    autofocus
                                    autocomplete="email"
                                    class="w-full h-12 px-4
                                           rounded-lg
                                           border border-slate-300
                                           bg-slate-50
                                           text-slate-800
                                           placeholder-slate-400
                                           outline-none
                                           transition
                                           focus:bg-white
                                           focus:border-[#0b2a4a]
                                           focus:ring-4
                                           focus:ring-[#0b2a4a]/10"
                                >

                            </div>


                            {{-- Password --}}
                            <div>

                                <div
                                    class="flex items-center
                                           justify-between mb-2"
                                >

                                    <label
                                        for="password"
                                        class="text-sm font-semibold
                                               text-slate-700"
                                    >
                                        Password
                                    </label>

                                    <a
                                        href="#"
                                        class="text-xs font-medium
                                               text-[#0b2a4a]
                                               hover:text-[#d4af37]
                                               transition"
                                    >
                                        Forgot Password?
                                    </a>

                                </div>


                                <div class="relative">

                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        placeholder="Enter your password"
                                        required
                                        autocomplete="current-password"
                                        class="w-full h-12 px-4 pr-12
                                               rounded-lg
                                               border border-slate-300
                                               bg-slate-50
                                               text-slate-800
                                               placeholder-slate-400
                                               outline-none
                                               transition
                                               focus:bg-white
                                               focus:border-[#0b2a4a]
                                               focus:ring-4
                                               focus:ring-[#0b2a4a]/10"
                                    >

                                    <button
                                        type="button"
                                        onclick="togglePassword()"
                                        class="absolute right-4
                                               top-1/2
                                               -translate-y-1/2
                                               text-slate-400
                                               hover:text-[#0b2a4a]"
                                    >

                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.8"
                                            stroke="currentColor"
                                            class="w-5 h-5"
                                        >

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943
                                                   7.523 5 12 5c4.478 0
                                                   8.268 2.943 9.542 7
                                                   -1.274 4.057-5.064
                                                   7-9.542 7-4.477
                                                   0-8.268-2.943
                                                   -9.542-7Z"
                                            />

                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0
                                                   3 3 0 0 1 6 0Z"
                                            />

                                        </svg>

                                    </button>

                                </div>

                            </div>


                            {{-- Remember --}}
                            <div class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    id="remember"
                                    name="remember"
                                    class="w-4 h-4
                                           rounded
                                           border-slate-300
                                           text-[#0b2a4a]
                                           focus:ring-[#0b2a4a]"
                                >

                                <label
                                    for="remember"
                                    class="text-sm text-slate-600"
                                >
                                    Remember me
                                </label>

                            </div>


                            {{-- Login Button --}}
                            <button
                                type="submit"
                                class="w-full h-12
                                       rounded-lg
                                       bg-[#0b2a4a]
                                       hover:bg-[#123b63]
                                       text-white
                                       font-semibold
                                       tracking-wide
                                       transition
                                       shadow-md
                                       shadow-[#0b2a4a]/20
                                       cursor-pointer"
                            >
                                SIGN IN
                            </button>

                        </form>


                        {{-- Divider --}}
                        <div class="flex items-center gap-4 my-7">

                            <div class="flex-1 h-px bg-slate-200"></div>

                            <span class="text-xs text-slate-400">
                                OR
                            </span>

                            <div class="flex-1 h-px bg-slate-200"></div>

                        </div>


                        {{-- Register --}}
                        <div class="text-center">

                            <p class="text-sm text-slate-500">
                                Don't have an account?
                            </p>

                            <a
                                href="{{ route('register') }}"
                                class="inline-block mt-2
                                       font-semibold
                                       text-[#0b2a4a]
                                       hover:text-[#d4af37]
                                       transition"
                            >
                                Create a new account →
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Help --}}
                <p
                    class="text-center text-xs
                           text-slate-500 mt-6"
                >
                    Need help?
                    <a
                        href="#"
                        class="font-medium
                               text-[#0b2a4a]"
                    >
                        Contact Administration
                    </a>
                </p>

            </div>

        </main>


        {{-- ================= FOOTER ================= --}}
        <footer class="bg-[#0b2a4a]">

            <div
                class="max-w-7xl mx-auto
                       px-6 lg:px-10
                       py-5"
            >

                <div
                    class="flex flex-col sm:flex-row
                           items-center
                           justify-between gap-3"
                >

                    <p class="text-xs text-white/60">
                        © {{ date('Y') }} IIT Indore
                        All Rights Reserved.
                    </p>

                    <div class="flex gap-5">

                        <a
                            href="#"
                            class="text-xs text-white/60
                                   hover:text-white"
                        >
                            Privacy Policy
                        </a>

                        <a
                            href="#"
                            class="text-xs text-white/60
                                   hover:text-white"
                        >
                            Terms
                        </a>

                        <a
                            href="#"
                            class="text-xs text-white/60
                                   hover:text-white"
                        >
                            Help
                        </a>

                    </div>

                </div>

            </div>

        </footer>

    </div>


    <script>

        function togglePassword() {

            const password =
                document.getElementById('password');

            password.type =
                password.type === 'password'
                    ? 'text'
                    : 'password';
        }

    </script>

</body>

</html>