<aside id="footer-widgets">
    <div class="container">

    </div>
</aside>
<footer id="footer">
    <div class="container">
        <div class="links-col">
            <div class="row">
                <div class="col-xs-4">
                    <h5>guest</h5>
                    <ul class="v-links">
                        <li><a href="#">login</a></li>
                        <li><a href="#">sign up</a></li>
                        <li><a href="#">customer support</a></li>
                        <li><a href="#">information</a></li>
                    </ul>
                </div>
                <div class="col-xs-4">
                    <h5>information</h5>
                    <ul class="v-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Delivery Informations</a></li>
                        <li><a href="#">Terms and conditions</a></li>
                        <li><a href="#">Return Policy</a></li>
                        <li><a href="#">Shipping and Deliveries</a></li>
                        <li><a href="#">Enquiries</a></li>
                    </ul>
                </div>
                <div class="col-xs-4">
                    <h5>member</h5>
                    <ul class="v-links">
                        <li><a href="#">Account</a></li>
                        <li><a href="#">Wishlist and Favourites</a></li>
                        <li><a href="#">Purchase History</a></li>
                        <li><a href="#">View Cart</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom">
        <div class="container">
            <div class="row">
                <div class="col-xs-6">
                    <span class="copy-text">@lang('core::core.message.copyright')</span>
                </div>
                <div class="col-xs-6">
                    <div class="socials text-right">
                        <a href="http://vk.com/monetype" class="rounded-icon social icon-vkontakte"><!-- vkontakte --></a>
                        <a href="#" class="rounded-icon social icon-facebook"><!-- facebook --></a>
                        <a href="#" class="rounded-icon social icon-twitter"><!-- twitter --></a>
                        <a href="#" class="rounded-icon social icon-gplus"><!-- google plus --></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@if(config('app.debug'))
    @include('core::layout.partials.profiler')
@endif