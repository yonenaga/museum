<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{if $title != ""}{$title} - MUSEUM{else}ARTIST{/if}</title>
    <link type="text/css" rel="stylesheet" href="museum.css" />
    <link rel="shortcut icon" href="../favicon.ico" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <style>
    table {
    	border-spacing: 0px 2px;
    }
</style>
</head>
<body>
<div align=left>
{if $artist != ""}
<table>
	<th><td colspan=2></td>{for $i=15 to 21}<td colspan=100 align=center>{$i}</td>{/for}</th>
	{foreach from=$artist key=key item=row name=loop}{if $row != null}
	<tr>
		<!-- <td><a href='https://ja.wikipedia.org/wiki/{$row[1]}' target='_blank'><nobr>{$row[1]}</nobr></a></td><td>&nbsp;({$row[5]}～{$row[6]})&nbsp;</td> -->
		{for $i=1400 to 2100}
		{* {if $i == $row[5] - 100}<td colspan=100 align=right>{$row[1]}{assign var="i" value=$i+100}</td>{/if} *}
		<td 
		{if $i % 100 == 0}bgcolor=lightgray{elseif $row[5]<=$i and $i<=$row[6]}bgcolor=
			{if $row[4] eq 'JPN'}red
			{elseif $row[4] eq 'FRA'}blue
			{elseif $row[4] eq 'ITA'}green
			{elseif $row[4] eq 'NED'}orange
			{else}black
			{/if}
		{/if}></td>
		{assign var="w" value=140}
		{* {if $i == $row[6] + 1}<td colspan={2000 - $row[6]}>{$row[1]}</td>{break}{/if} *}
		{if $i == $row[6] + 1}<td colspan={$w}><a href='https://ja.wikipedia.org/wiki/{$row[1]}' target='_blank'><nobr>{$row[1]}</nobr></a></td>{assign var="i" value=$i+$w}{/if}
		{/for}
	</tr>
	{/if}{/foreach}
</table>
{/if}
