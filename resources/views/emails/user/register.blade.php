@extends('emails.template')
@section('content')
<table width="700" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
	<tr>
		<td width="100%">
			<h3>Здраствуйте</h3>
			<p>Мы рады приветствовать вас на нашем сайте - MoneType.ru. Здесь вы сможете публиковать интересные статьи
				и получать за них реальные деньги.</p>

			<p>Спасибо за регистрацию</p>
		</td>
	</tr>
</table>

<table width="700" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
	<tr>
		<td class="center" bgcolor="#f7f7f7" style="font-size: 14px; color: #303030; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 50px 50px 20px; " >
			В качестве бонуса мы дарим Вам {{ $balance }}$ на счет, которые вы сможете
			потратить на публикацию и чтение стетей.
		</td>
	</tr>
</table>
@endsection