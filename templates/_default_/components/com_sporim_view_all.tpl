<link href="/components/sporim/sporim.css" rel="stylesheet" type="text/css">
<div class="topspor">
<div class="logspor"><img src="/components/sporim/i/logo.jpg"></div>
<div class="tspor" align="justify">Спор — это столкновение мнений, в ходе которого одна из сторон (или обе) стремится убедить другую в справедливости своей позиции. Cпор — это публичное обсуждение проблем, интересующих участников обсуждения, вызванное желанием как можно глубже…</div>
</div><br><br>
{if $messages}
{foreach key=id item=m from=$messages name=foo}
{if $smarty.foreach.foo.index % 2}
<div class="table_1">
<table width="100%" border="0">
	<tr>
		<td width="200" align="center">
			<form action="/sporim/da" method="post">
				<input type="hidden" name="s_id" value="{$m.id}">
					<div id="but1">
						<input class="vote" value="Соглашусь! " type="submit">
					</div>
			</form>
			<br><br>
			<form action="/sporim/no" method="post">
				<input type="hidden" name="s_id" value="{$m.id}">
					<div id="but2">
						<input class="vote" value=" Я против!  " type="submit">
					</div>
			</form>
		</td>
		<td width="750" valign="top" align="justify"><div class="cramka">
			<a href="/sporim/{$m.id}"><div class="stitle">{$m.title}</div>
			<br><br>
			<div class="sdesc">{$m.description}</div></a>
		</td>
		<td width="210" align="center" valign="top"><div class="pugol">
			<div class="asporim"><b>{$m.nickname}</b><br></div>
			<a href="/users/{$m.login}">
			{if $m.imageurl}
			<img class="isporim" src="/images/users/avatars/small/{$m.imageurl}">
			{else}
			<img class="isporim" src="/images/users/avatars/small/nopic.jpg">
			{/if}
			</a>
			<br><div class="dsporim">Cоздано: <br>{$m.pubdate}</div></div>
		</td>
	</tr>
</table>
</div>
{else}
<div class="table_2">
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
		</td>
		<td width="750" valign="top" align="justify"><div class="cramka">
			<a href="/sporim/{$m.id}"><div class="stitle">{$m.title}</div>
			<br>
			<div class="sdesc">{$m.description}</div></div></a>
		</td>
		<td width="200" align="center">
			<form action="/sporim/da" method="post">
				<input type="hidden" name="s_id" value="{$m.id}">
					<div id="but1">
						<input class="vote" value="Соглашусь! " type="submit">
					</div>
			</form>
			<br><br>
			<form action="/sporim/no" method="post">
				<input type="hidden" name="s_id" value="{$m.id}">
					<div id="but2">
						<input class="vote" value="  Я против!  " type="submit">
					</div>
			</form>	
		</td>
	</tr>
</table>
</div>
{/if}
<a href="/sporim/{$m.id}"><div class="comspor">Комментарии ({$m.comov})</div></a>
<br>
<br>

{assign var=t value=$m.da+$m.no}
{assign var="cto" value="100"}
{assign var=to value=$cto/$t}
{assign var=kr value=$to*$m.no}
{assign var=ze value=$to*$m.da}
<table width="100%">
	<tr>
		<td class="zele" width="{$ze}%" height="2"></td>
		<td class="kras" width="{$kr}%"></td>
	</tr>
	<tr>
		<td>{$m.da}</td>
		<td align="right">{$m.no}</td>
	</tr>
</table>
<br>
{/foreach}

{/if}
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="2" style="margin-bottom:10px"><tr><td align="right"><img border="0" src="/components/sporim/i/add.gif"></td><td width="170"><a href="/sporim/add" style="text-decoration:underline">Создать новый прецендент</a></td></tr></table>