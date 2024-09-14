<footer class="footer">
    <div class="container py-4 py-md-5">
        <div class="row gutters-1">
            <div class="col-lg-3 col-md-3 col-6 mb-3 mb-lg-0">
                <a href="{{ url('/about-us') }}">About us</a>
            </div>
            <div class="col-lg-3 col-md-3 col-6 mb-3 mb-lg-0">
                <a href="{{ url('/terms-condition') }}">Terms & condition</a>
            </div>
            <div class="col-lg-3 col-md-3 col-6 mb-3 mb-lg-0">
                <a href="{{ url('/privacy-policy') }}">Privacy & Policy</a>
            </div>
            <div class="col-lg-3 col-md-3 col-6 mb-3 mb-lg-0">
                <a href="{{ url('/return-policy') }}">Return Policy</a>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-left">
                    <span>Copyright &copy; {{ date('Y') }}
                </div>
                <div class="col-lg-6 text-center text-lg-right mt-2 mt-lg-0">
                    <span><img src="{{ asset('front/images/payment-cards/western-union.svg') }}"></span>
                    <span><img src="{{ asset('front/images/payment-cards/discover.svg') }}"></span>
                    <span><img src="{{ asset('front/images/payment-cards/paypal.svg') }}"></span>
                    <span><img src="{{ asset('front/images/payment-cards/visa.svg') }}"></span>
                </div>
            </div>
        </div>
    </div>
</footer>
