<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Register | Student Portal</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body class="min-h-screen bg-slate-100">

<div class="min-h-screen flex flex-col">


    {{-- ================= HEADER ================= --}}
    <header class="bg-white border-b border-slate-200">

        <div class="max-w-7xl mx-auto px-6 lg:px-10">

            <div class="h-20 flex items-center justify-between">

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

                        <h1
                            class="text-lg md:text-xl
                                   font-bold
                                   text-[#0b2a4a]
                                   tracking-wide"
                        >
                            IIT Indore
                        </h1>

                        <p class="text-xs text-slate-500">
                            Excellence • Knowledge • Character
                        </p>

                    </div>

                </div>


                <a
                    href="/"
                    class="hidden sm:block
                           text-sm font-medium
                           text-slate-600
                           hover:text-[#0b2a4a]"
                >
                    IITI Website
                </a>

            </div>

        </div>

    </header>


    {{-- ================= MAIN ================= --}}
    <main
        class="flex-1 flex items-center
               justify-center
               px-5 py-10
               relative overflow-hidden"
    >

        {{-- Decorative background --}}
        <div
            class="absolute -top-32 -left-32
                   w-96 h-96 rounded-full
                   bg-[#0b2a4a]/5
                   pointer-events-none"
        ></div>

        <div
            class="absolute -bottom-40 -right-40
                   w-[500px] h-[500px]
                   rounded-full
                   bg-[#d4af37]/10
                   pointer-events-none"
        ></div>


        <div class="relative w-full max-w-lg">


            {{-- Register Card --}}
            <div
                class="bg-white rounded-2xl
                       border border-slate-200
                       shadow-xl
                       shadow-slate-900/5
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
                               flex items-center
                               justify-center
                               text-[#0b2a4a]
                               text-2xl font-bold
                               border-4 border-[#d4af37]"
                    >
                        C
                    </div>

                    <h2 class="text-2xl font-bold text-white">
                        Create Account
                    </h2>

                    <p class="mt-1 text-sm text-white/70">
                        Register for the college portal
                    </p>

                </div>


                <div class="p-8">


                    {{-- Errors --}}
                    @if ($errors->any())

                        <div
                            class="mb-6
                                   rounded-lg
                                   border border-red-200
                                   bg-red-50
                                   px-4 py-3"
                        >

                            <ul
                                class="text-sm
                                       text-red-600
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
                        method="POST"
                        action="{{ route('register') }}"
                        class="space-y-5"
                    >

                        @csrf


                        {{-- Name --}}
                        <div>

                            <label
                                for="name"
                                class="block mb-2
                                       text-sm font-semibold
                                       text-slate-700"
                            >
                                Full Name
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="Enter your full name"
                                required
                                autocomplete="name"
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


                        {{-- Email --}}
                        <div>

                            <label
                                for="email"
                                class="block mb-2
                                       text-sm font-semibold
                                       text-slate-700"
                            >
                                College Email Address
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="example@college.edu"
                                required
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

                            <label
                                for="password"
                                class="block mb-2
                                       text-sm font-semibold
                                       text-slate-700"
                            >
                                Password
                            </label>

                            <div class="relative">

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    placeholder="Create a password"
                                    required
                                    autocomplete="new-password"
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
                                    onclick="togglePassword('password')"
                                    class="absolute right-4
                                           top-1/2
                                           -translate-y-1/2
                                           text-slate-400
                                           hover:text-[#0b2a4a]"
                                >
                                    👁
                                </button>

                            </div>

                            <p class="mt-2 text-xs text-slate-400">
                                Password must contain at least 8 characters.
                            </p>

                        </div>


                        {{-- Confirm Password --}}
                        <div>

                            <label
                                for="password_confirmation"
                                class="block mb-2
                                       text-sm font-semibold
                                       text-slate-700"
                            >
                                Confirm Password
                            </label>

                            <div class="relative">

                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Confirm your password"
                                    required
                                    autocomplete="new-password"
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
                                    onclick="togglePassword(
                                        'password_confirmation'
                                    )"
                                    class="absolute right-4
                                           top-1/2
                                           -translate-y-1/2
                                           text-slate-400
                                           hover:text-[#0b2a4a]"
                                >
                                    👁
                                </button>

                            </div>

                        </div>


                        {{-- Terms --}}
                        <div class="flex items-start gap-3">

                            <input
                                type="checkbox"
                                id="terms"
                                required
                                class="mt-1 w-4 h-4
                                       rounded
                                       border-slate-300
                                       text-[#0b2a4a]
                                       focus:ring-[#0b2a4a]"
                            >

                            <label
                                for="terms"
                                class="text-sm
                                       text-slate-500
                                       leading-relaxed"
                            >

                                I agree to the
                                <a
                                    href="#"
                                    class="font-medium
                                           text-[#0b2a4a]"
                                >
                                    Terms of Service
                                </a>

                                and

                                <a
                                    href="#"
                                    class="font-medium
                                           text-[#0b2a4a]"
                                >
                                    Privacy Policy
                                </a>.

                            </label>

                        </div>


                        {{-- Register Button --}}
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
                                   shadow-[#0b2a4a]/20"
                        >
                            CREATE ACCOUNT
                        </button>

                    </form>


                    {{-- Login --}}
                    <div
                        class="mt-7 pt-6
                               border-t border-slate-200
                               text-center"
                    >

                        <p class="text-sm text-slate-500">

                            Already registered?

                            <a
                                href="{{ route('login') }}"
                                class="font-semibold
                                       text-[#0b2a4a]
                                       hover:text-[#d4af37]
                                       transition"
                            >
                                Sign in
                            </a>

                        </p>

                    </div>

                </div>

            </div>


            <p
                class="text-center text-xs
                       text-slate-500 mt-5"
            >
                Registration assistance:
                <span class="font-medium text-[#0b2a4a]">
                    College Administration
                </span>
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
                    © {{ date('Y') }} Your College Name.
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

    function togglePassword(id) {

        const input = document.getElementById(id);

        input.type =
            input.type === 'password'
                ? 'text'
                : 'password';

    }

</script>

</body>

</html>