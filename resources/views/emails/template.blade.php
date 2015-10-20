<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>{{ $title or 'MoneType - Сервис качественного контента' }}</title>

	<style type="text/css">
		.ReadMsgBody {width: 100%; background-color: #ffffff;}
		.ExternalClass {width: 100%; background-color: #ffffff;}
		body     {width: 100%; background-color: #ffffff; margin:0; padding:0; -webkit-font-smoothing: antialiased;font-family: Arial, Helvetica, sans-serif}
		table {border-collapse: collapse;}

		@media only screen and (max-width: 640px)  {
			body[yahoo] .deviceWidth {width:440px!important; padding:0;}
			body[yahoo] .center {text-align: center!important;}
		}

		@media only screen and (max-width: 479px) {
			body[yahoo] .deviceWidth {width:280px!important; padding:0;}
			body[yahoo] .center {text-align: center!important;}
		}
	</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" yahoo="fix" style="font-family: Arial, Helvetica, sans-serif">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr>
			<td width="100%" valign="top" bgcolor="#ffffff" style="padding-top:20px">
				<table width="700" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
					<tr>
						<td width="100%">
							{!! link_to('/', HTML::image('img/logo-sm.png')) !!}
						</td>
					</tr>
				</table>

				@yield('content')

				<table width="700" border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
					<tr>
						<td width="100%" bgcolor="#a5d1da" class="center">
							<table  border="0" cellpadding="0" cellspacing="0" align="center">
								<tr>
									<td valign="top" style="padding: 20px 10px " class="center">
										<a href="#"><img width="32" hight="32" src="{{ url('img/icons/icon_facebook.png') }}"></a>
									</td>
									<td valign="top" style="padding: 20px 10px " class="center">
										<a href="#"><img width="32" hight="32" src="{{ url('img/icons/icon_twitter.png') }}"></a>
									</td>
								</tr>
							</table>
							<table  border="0" cellpadding="0" cellspacing="0" align="center">
								<tr>
									<td  class="center" style="font-size: 16px; color: #ffffff; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 0px 10px; ">
										Stay Involved With Us
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>

				<table width="700"  border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth"  >
					<tr>
						<td  bgcolor="#ffffff" class="center" style="font-size: 12px; color: #687074; font-weight: bold; text-align: center; font-family: Arial, Helvetica, sans-serif; line-height: 25px; vertical-align: middle; padding: 20px 50px 0px 50px; " >
							{{ date('Y') }} © MoneType. ALL Rights Reserved.
						</td>
					</tr>
				</table>

			</td>
		</tr>
	</table>
</body>
</html>