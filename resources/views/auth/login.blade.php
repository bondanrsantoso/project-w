@extends('auth.layouts.app')

@section('content')
<section class="vh-100" style="background-color: #508bfc;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                @if($errors->any())
                    @foreach($errors->all() as $error)
                    <div class="alert alert-danger text-center" role="alert">
                        {{ $error }}
                    </div>
                    @endforeach
                @endif
                <div class="card shadow-2-strong" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <h3 class="mb-5">Sign in</h3>
                        <form action="/auth/login" method="POST">
                            @csrf
                            
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="floatingInput" name="username"
                                    placeholder="Username">
                                <label for="floatingInput">Username</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="floatingPassword" name="password"
                                    placeholder="Password">
                                <label for="floatingPassword">Password</label>
                            </div>
                            
                            <button class="btn btn-primary btn-lg btn-block px-5" id="btn-submit"
                            type="submit">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
