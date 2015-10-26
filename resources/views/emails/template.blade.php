<table style="border-collapse:separate!important;border-spacing:0;width:100%;background:#f6f6f6" width="100%">
    <tbody>
    <tr>
        <td style="vertical-align:top" valign="top"></td>
        <td style="vertical-align:top;display:block;max-width:700px;width:700px;margin:0 auto;padding:10px" valign="top">
            <div style="display:block;max-width:700px;margin:0 auto;padding:10px">
                <table style="border-collapse:separate!important;border-radius:3px;border-spacing:0;width:100%;background:#fff;border:1px solid #e9e9e9" width="100%">
                    <tbody>
                    <tr>
                        <td style="vertical-align:top;padding:30px 30px 10px" valign="top">

                            <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
                                <tr>
                                    <td>
                                        {!! link_to('/', HTML::image('img/logo-sm.png')) !!}
                                    </td>
                                </tr>
                            </table>

                            @yield('content')

                            <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
                                <tr>
                                    <td bgcolor="#471F5D" class="center">
                                        <table border="0" cellpadding="0" cellspacing="0" align="center">
                                            <tr>
                                                <td valign="top" style="padding: 10px " class="center">
                                                    <a href="http://vk.com/monetype"><img width="30" height="30" src="{{ url('img/icons/icon_vk.png') }}"></a>
                                                </td>
                                                <td valign="top" style="padding: 10px" class="center">
                                                    <a href="https://www.facebook.com/groups/monetype/"><img width="30" height="30" src="{{ url('img/icons/icon_facebook.png') }}"></a>
                                                </td>
                                                <td valign="top" style="padding: 10px" class="center">
                                                    <a href="https://twitter.com/monetyperu"><img width="30" height="30" src="{{ url('img/icons/icon_twitter.png') }}"></a>
                                                </td>
                                                <td valign="top" style="padding: 10px" class="center">
                                                    <a href="https://plus.google.com/u/0/communities/103808343606054010691/members"><img width="30" height="30" src="{{ url('img/icons/icon_gplus.png') }}"></a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
                                <tr>
                                    <td class="center" style="font-size: 11px; color: #687074; text-align: center; line-height: 25px; vertical-align: middle; padding: 10px 0 0; ">
                                        {{ date('Y') }} © MoneType. ALL Rights Reserved.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </td>
        <td style="vertical-align:top" valign="top"></td>
    </tr>
    </tbody>
</table>