<aside id="footer-widgets">
    <div class="container">
        <div class="socials text-right">
            <a href="#" class="rounded-icon social fa fa-facebook"><!-- facebook --></a>
            <a href="#" class="rounded-icon social fa fa-twitter"><!-- twitter --></a>
            <a href="#" class="rounded-icon social fa fa-google-plus"><!-- google plus --></a>
        </div>
    </div>
</aside>
<footer id="footer">
    <p>© {{ date('Y') }} MoneType, inc. All rights reserved.</p>
</footer>
@if(config('app.debug'))
@include('core::layout.partials.profiler')
@endif