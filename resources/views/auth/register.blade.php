<x-guest-layout>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
        <div class="container-fluid py-1 px-3">
            <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <a href="/" class="navbar-brand font-weight-bolder ms-lg-0 ms-3">
                        Material Dashboard 3
                    </a>
                </div>
                <ul class="navbar-nav justify-content-end">
                    <li class="nav-item d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="nav-link font-weight-bold mx-lg-4 mx-2 px-0">
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a href="{{ route('profile') }}" class="nav-link font-weight-bold mx-lg-4 mx-2 px-0">
                            Profile
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a href="{{ route('register') }}" class="nav-link font-weight-bold mx-lg-4 mx-2 px-0">
                            Sign Up
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a href="{{ route('login') }}" class="nav-link font-weight-bold mx-lg-4 mx-2 px-0">
                            Sign In
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a href="#" class="nav-link font-weight-bold mx-lg-4 mx-2 px-0">
                            Online Builder
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a href="#" class="nav-link font-weight-bold mx-lg-4 mx-2 px-0">
                            Free download
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Sign Up</h4>
                                    <p class="mb-0">Enter your email and password to register</p>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf

                                        <!-- Name -->
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
                                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-danger" />
                                        </div>

                                        <!-- Email Address -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
                                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-danger" />
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
                                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-danger" />
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                                            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-danger" />
                                        </div>

                                        <!-- Terms and Conditions -->
                                        <div class="form-check form-check-info text-start">
                                            <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                            <label class="form-check-label" for="terms">
                                                I agree the <a href="#" class="text-dark font-weight-bolder">Terms and Conditions</a>
                                            </label>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-lg bg-gradient-primary btn-lg w-100 mt-4 mb-0">Sign Up</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        Already have an account?
                                        <a href="{{ route('login') }}" class="text-primary text-gradient font-weight-bold">Sign in</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden">
                                <span class="mask bg-gradient-primary opacity-6"></span>
                                <h4 class="mt-5 text-white font-weight-bolder">"Attention is All you Need"</h4>
                                <p class="text-white">Discover the power of our platform and unlock a world of possibilities.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-8 mx-auto text-center mt-1">
                    <p class="mb-0 text-secondary">
                        © <script>document.write(new Date().getFullYear())</script>,
                        made with <i class="fa fa-heart"></i> by
                        <a href="#" class="text-secondary font-weight-bold">Creative Tim</a>
                        for a better web.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-8 mx-auto text-center mt-1">
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-secondary">Creative Tim</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-secondary">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-secondary">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-secondary">License</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</x-guest-layout>