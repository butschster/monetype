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
                        <a href="https://www.facebook.com/groups/monetype/" class="rounded-icon social icon-facebook"><!-- facebook --></a>
                        <a href="https://twitter.com/monetyperu" class="rounded-icon social icon-twitter"><!-- twitter --></a>
                        <a href="https://plus.google.com/u/0/communities/103808343606054010691/members" class="rounded-icon social icon-gplus"><!-- google plus --></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
@if(config('app.debug'))
    @include('core::layout.partials.profiler')
@endif

@if(App::environment('production'))
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter33316058 = new Ya.Metrika({ id:33316058, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");
</script>
<noscript>
    <div>
        <img src="https://mc.yandex.ru/watch/33316058" style="position:absolute; left:-9999px;" alt="" />
    </div>
</noscript>
<!-- /Yandex.Metrika counter -->
@endif