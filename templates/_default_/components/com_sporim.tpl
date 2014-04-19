<link href="/components/sporim/sporim.css" rel="stylesheet" type="text/css">
<div class="topspor">
<div class="logspor"><img src="/components/sporim/i/logo.jpg"></div>
<div class="tspor" align="justify">Спор — это столкновение мнений, в ходе которого одна из сторон (или обе) стремится убедить другую в справедливости своей позиции. Cпор — это публичное обсуждение проблем, интересующих участников обсуждения, вызванное желанием как можно глубже…</div>
</div><br><br>
{if $messages}
{foreach key=id item=m from=$messages}

<table width="100%" border="0">
	<tr>
		<td width="210" align="center" valign="top"><div class="lugol">
			<div class="asporim"><b>{$m.nickname}</b><br></div>
			<a href="/users/{$m.login}">
			{if $m.imageurl}
			<img class="isporim" src="/images/users/avatars/small/{$m.imageurl}">
			{else}
			<img class="isporim" src="/images/users/avatars/small/nopic.jpg">
			{/if}
			</a>
			<br><div class="dsporim">Cоздано:<br> {$m.pubdate}</div></div>
			{$m.karma}
		</td>
		<td width="700" valign="top" align="justify"><div class="cramka">
			<div class="stitle">{$m.title}</div>
			<br>
			<div class="sdesc">{$m.description}</div></div>
		</td>
		<td width="200"></td>
	</tr>
	<tr>
		<td></td>
		{if $edited==1}
		<td align="right"><a href="/sporim/edit/{$m.id}"><em class="z"><b>Редактировать</b></em></a> | <a href="/sporim/delete/{$m.id}"><em class="k"><b>Удалить</b></em></a><br></td>
		{else}
		<td></td>
		{/if}
		<td></td>
	</tr>
	<tr>
		<td></td>
		<td>
{assign var=t value=$m.da+$m.no}
{assign var="cto" value="100"}
{assign var=to value=$cto/$t}
{assign var=kr value=$to*$m.no}
{assign var=ze value=$to*$m.da}
<table width="100%">
	<tr>
		<td align="center"><div style="color:#cccccc">|</div></td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td class="zele2" width="{$ze}%"></td>
		<td class="kras2" width="{$kr}%"></td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td align="center"><div style="color:#cccccc">|</div></td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td align="right"><div class="z">истина<br>где-то</div></td>
		<td><div class="k">всегда<br>рядом</div></td>
	</tr>
	<tr>
		<td width="50%" align="center"><form action="/sporim/da" method="post"><input type="hidden" name="s_id" value="{$m.id}"><div id="but1"><input class="vote" value="Соглашусь!" type="submit"></div></form></td>
		<td width="50%" align="center"><form action="/sporim/no" method="post"><input type="hidden" name="s_id" value="{$m.id}"><div id="but2"><input class="vote" value=" Я против! " type="submit"></div></form></td>
	</tr>	
</table>
<table width="100%">
	<tr>
		<td width="50%" align="center"><div class="brand-votes"><b style="color:#00ae00;">{$m.da}</b></div></td>
		<td width="50%" align="center"><div class="brand-votes"><b style="color:#F81F07;">{$m.no}</b></div></td>
	</tr>	
</table>		
		</td>
		<td></td>
	</tr>
	<tr>
		<td colspan="3">
		<table width="700" align="center" border="0">
			<tr>
				<td width="50%"></td>
				<td width="50%"></td>
			</tr>
		</table>
		
		</td>
	</tr>
</table>
<br>
<br>
<br>
{/foreach}
{/if}
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="margin-bottom:10px"><tr><td align="right"><img border="0" src="/components/sporim/i/add.gif"></td><td width="170"><a href="/sporim/add" style="text-decoration:underline">Создать новый прецендент</a></td></tr></table>