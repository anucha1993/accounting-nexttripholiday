<!doctype html>
<html lang="en">
  <head>
  	<title>Login 10</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="login-form-20/css/style.css">

	</head>
    {{-- style="background-image: url(login-form-20/images/bg.jpg);" --}}
	<body class="img js-fullheight" style="background-color: #57f1dd">
	<section class="ftco-section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-md-6 text-center mb-5">
					{{-- <img src="login-form-20/images/logo.png" alt="" style="width: 60%"> --}}
				</div>
			</div>
			<div class="row justify-content-center ">
				<div class="col-md-6 col-lg-4 ">
                    <img src="login-form-20/images/logo.png" alt="" style="width: 100%">
					<div class="login-wrap p-0">
                        <div class="row justify-content-center">
                            <div class="col-md-8 text-center mb-5">
                               
                            </div>
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
		      		<div class="form-group">
                        <input id="email" type="email" placeholder="Email" class="form-control  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        
                          @error('email')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror

		      		</div>
	            <div class="form-group">
                    <input id="password-field" type="password" class="form-control" name="password" placeholder="Password" required>
                    <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                    
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
	              <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
	            </div>
	            <div class="form-group">
	            	<button type="submit" class="form-control submit border px-3" style="border: #fff: border">Sign In</button>
	            </div>
	            <div class="form-group d-md-flex">
	            	<div class="w-50">
		            	<label class="checkbox-wrap checkbox-primary"  style="color: #fff">Remember Me
									  <input type="checkbox" checked>
									  <span class="checkmark"></span>
									</label>
								</div>
								<div class="w-50 text-md-right">
									<a href="#" style="color: #fff">Forgot Password</a>
								</div>
	            </div>
	          </form>
	          <p class="w-100 text-center">&mdash; Accounting Nexttripholiday &mdash;</p>
	          {{-- <div class="social d-flex text-center">
	          	<a href="#" class="px-2 py-2 mr-md-1 rounded"><span class="ion-logo-facebook mr-2"></span> Facebook</a>
	          	<a href="#" class="px-2 py-2 ml-md-1 rounded"><span class="ion-logo-twitter mr-2"></span> Twitter</a> --}}
	          </div>
		      </div>
				</div>
			</div>
		</div>
	</section>

	<script src="login-form-20/js/jquery.min.js"></script>
  <script src="login-form-20/js/popper.js"></script>
  <script src="login-form-20/js/bootstrap.min.js"></script>
  <script src="login-form-20/js/main.js"></script>

	</body>
</html>

