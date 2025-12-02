<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CPAT - ADMIN</title>
    @routes
    @vite(['resources/js/app.js','resources/css/app.css'])
    @inertiaHead
</head>

<body class="min-h-screen antialiased bg-[linear-gradient(0deg,hsla(197,14%,57%,1)_0%,hsla(192,17%,94%,1)_30%)] text-base">
    <div class="flex flex-col ">

        <!-- Header -->
        <header class="bg-white shadow m-5 rounded-4xl">
            <div class="navbar bg-base-100 shadow-md">
                <div class="flex-1">
                    <a class="btn btn-ghost text-xl">daisyUI</a>
                </div>
                <div class="flex gap-2">
                    <input type="text" placeholder="Search" class="input input-bordered w-24 md:w-auto" />
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full">
                                <img
                                    alt="Tailwind CSS Navbar component"
                                    src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                            </div>
                        </div>
                        <ul
                            tabindex="-1"
                            class="menu menu-md dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                            <li>
                                <a class="justify-between">
                                    Profile
                                    <span class="badge">New</span>
                                </a>
                            </li>
                            <li><a>Settings</a></li>
                            <li><a>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex min-h-[calc(100vh-200px)] m-5 rounded">

            <!-- Left Sidebar -->
            <aside class="w-64 bg-white p-4 mr-10 shadow-md">
                <nav class="space-y-2">
                    <a href="#" class="block p-2 rounded hover:bg-gray-200">Dashboard</a>
                    <a href="#" class="block p-2 rounded hover:bg-gray-200">Patients</a>
                    <a href="#" class="block p-2 rounded hover:bg-gray-200">Doctors</a>
                    <a href="#" class="block p-2 rounded hover:bg-gray-200">Appointments</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6 bg-white shadow-md">
                <div class="w-full p-3 grid grid-cols-5 gap-4">

                    <select class="select bg-gray-50 border-black">
                        <option disabled selected>Pick Algorithm</option>
                        <option>PA-CAT V9</option>
                    </select>
                    <label class="floating-label">
                        <span>User</span>
                        <input type="text" placeholder="User" value="rajeshphilip0824@gmail.com" class="input input-md bg-gray-50 border-black" />
                    </label>
                    <label class="floating-label">
                        <span>Expiry</span>
                        <input type="text" placeholder="Expiry" value="10-Dec-2025" class="input input-md bg-gray-50 border-black" />
                    </label>
                    <label class="floating-label">
                        <span>Company</span>
                        <input type="text" placeholder="Company" value="Preset" class="input input-md bg-gray-50 border-black" />
                    </label>
                    <button class="btn btn-neutral w-1/2 justify-center text-white">
                        Reset
                    </button>
                </div>
                <div class="divider"></div>
                @inertia

            </main>

        </div>
        <footer class="footer sm:footer-horizontal bg-neutral text-neutral-content items-center p-4">
            <aside class="grid-flow-col items-center">
                <svg
                    width="36"
                    height="36"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    class="fill-current">
                    <path
                        d="M22.672 15.226l-2.432.811.841 2.515c.33 1.019-.209 2.127-1.23 2.456-1.15.325-2.148-.321-2.463-1.226l-.84-2.518-5.013 1.677.84 2.517c.391 1.203-.434 2.542-1.831 2.542-.88 0-1.601-.564-1.86-1.314l-.842-2.516-2.431.809c-1.135.328-2.145-.317-2.463-1.229-.329-1.018.211-2.127 1.231-2.456l2.432-.809-1.621-4.823-2.432.808c-1.355.384-2.558-.59-2.558-1.839 0-.817.509-1.582 1.327-1.846l2.433-.809-.842-2.515c-.33-1.02.211-2.129 1.232-2.458 1.02-.329 2.13.209 2.461 1.229l.842 2.515 5.011-1.677-.839-2.517c-.403-1.238.484-2.553 1.843-2.553.819 0 1.585.509 1.85 1.326l.841 2.517 2.431-.81c1.02-.33 2.131.211 2.461 1.229.332 1.018-.21 2.126-1.23 2.456l-2.433.809 1.622 4.823 2.433-.809c1.242-.401 2.557.484 2.557 1.838 0 .819-.51 1.583-1.328 1.847m-8.992-6.428l-5.01 1.675 1.619 4.828 5.011-1.674-1.62-4.829z"></path>
                </svg>
                <p>Copyright © {{ date('Y') }} - All right reserved</p>
            </aside>
            <nav class="grid-flow-col gap-4 md:place-self-center md:justify-self-end">
                <a>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        class="fill-current">
                        <path
                            d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"></path>
                    </svg>
                </a>
                <a>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        class="fill-current">
                        <path
                            d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"></path>
                    </svg>
                </a>
                <a>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        class="fill-current">
                        <path
                            d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"></path>
                    </svg>
                </a>
            </nav>
        </footer>
    </div>

</body>

</html>