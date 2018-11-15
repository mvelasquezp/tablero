<table style="border-collapse:collapse;border-width:0;">
	<tr>
		<td style="background-color:#e0e0e0;vertical-align:bottom;text-align:left;padding:10px 40px 10px 10px;">
			<img src="{{ asset('public/images/icons/logo_minsa.png') }}" style="width:128px;">
		</td>
		<td style="padding:10px;">
			<h1 style="font-family:Verdana;color:#0d47a1;">{{ $mensaje->titulo }} {{ $nombre }}</h1>
			<hr>
			<p style="font-family:Verdana;color:#808080;">{{ $mensaje->cuerpo }}. Podrás acceder desde este <a href="{{ url('/') }}">enlace</a> utilizando las siguientes credenciales:</p>
			<p style="font-family:Verdana;color:#808080;">Usuario: <b>{{ $usuario }}</b></p>
			<p style="font-family:Verdana;color:#808080;">Clave: <b>{{ $clave }}</b></p>
			<p style="font-family:Verdana;color:#808080;margin-top:24px;">{{ $mensaje->despedida }}</p>
		</td>
	</tr>
</table>
