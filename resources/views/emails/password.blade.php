@extends('emails.template')
@section('content')
    <table width="700" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
        <tr>
            <td>
                <h3>Сброс пароля</h3>
                <p>Для сброса пароля перейдите по ссылке: {!! link_to("password/reset/$token") !!}</p>
            </td>
        </tr>
    </table>
@endsection