<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{if $header_title != ""}{$header_title} - MUSEUM{else}MUSEUM{/if}</title>
    <link type="text/css" rel="stylesheet" href="museum.css" />
    <link rel="icon" href="favicon.ico" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" />
<script type="text/javascript" >
$(function(){
    $("#year").change(function(){
        console.log("aaa");
        $("#year").submit();
    });
});
</script>
 -->
<script>
<!--
function getyear() {
    document.museum.submit();
}
-->
</script>
</head>
<body>
<div align=center>
   	<table>
        <tr>
            {section name=i loop=$label}
            <td style="padding-right: 2px"><a href='{$smarty.const.URL}?mode=1&title={$label[i]}&pref={$pref[i]}'>{$label[i]}</a></td>
            {/section}
        </tr>
        <tr>
            {section name=i loop=$label}
            <td><a href='{$smarty.const.URL}?mode=2&title={$label[i]}&pref={$pref[i]}'>{$label[i]}</a></td>
            {/section}
        </tr>
    </table>
    <table>
        <tr>
            <td style="padding-right: 3px"><a href='{$smarty.const.URL}?mode=2&title=PLAN&plan=2'>PLAN</a></td>
            <td style="padding-right: 3px"><a href='{$smarty.const.URL}?mode=2&title=NOW&now=2'>NOW</a></td>
            <td align=center style="padding-right: 3px"><a href='{$smarty.const.URL}?mode=1&title=フランス人がときめいた日本の美術館&fra=1'>FRA</a></td>
            {foreach from=$year_options item=year}
	            <td style="padding-right: 3px"><a href='{$smarty.const.URL}?mode=2&title={$year}&year={$year}'>{$year}</a></td>
		        {if $year eq "2013"}</tr><tr>{/if}
            {/foreach}
        </tr>
    </table>
<BR>

<h1>{$title}</h1>

{if $museum != ""}
    <h3>{$museum[8]}</h3>
    <table>
    <tr><td align=right>Webサイト　</td><td><a href="{$museum[7]}" target="_blank">{$museum[7]}</a></td></tr>
    <tr><td align=right>開館時間　</td><td>{$museum[1]}～{$museum[2]}</td></tr>
    <tr><td align=right>休館日　</td><td>{$museum[3]}</td></tr>
    <tr><td align=right>割引　</td><td>
		{if $museum[4] eq "JAF"}<img src="./jaf.png" height=14 alt="JAF" >{elseif $museum[4] eq "交通系IC"}<img src="./ic.gif" height=16 alt="IC" >{else}{$museum[4]}{/if}
		</td></tr>
    <tr><td align=right rowspan=3>支払　</td><td>入場料: {$museum[10]}</td></tr>
    <tr><td>特設グッズ売場: {$museum[11]}</td></tr>
    <tr><td>ミュージアムショップ: {$museum[12]}</td></tr>
    <tr><td align=right>年間スケジュール　</td><td><a href="{$museum[9]}" target="_blank">{$museum[9]}</a></td></tr>
    <tr><td align=right>備考　</td><td>{$museum[6]}</td></tr>
    </table>
{/if}
<br>

{if $expedition != ""}
    <table>
        <tr><!-- <td align=right>宿泊</td> --><td>{$expedition[0]}</td></tr>
    </table>    
    <br>
{/if}

{* 展覧会一覧 *}
{if $exhibit != ""}
    <table>
    {foreach from=$exhibit key=key item=row2 name=loop}
    {if $row2 != null}
    <tr bgcolor="{cycle values="#FFF0F5,#F0FFFF"}">
    {* <td align="right">{$smarty.foreach.loop.iteration}</td> *}
    <td align="right">{$row2[7]}</td>
    <td align="right">{$row2[8]}</td>
    {if $title neq "PLAN"}
    	<td align="right">{$row2[18]}</td>
    {/if}
    {if $year eq null}
   		<td align="left">{if $row2[4] neq ""}{$row2[4]}{else}{if $title neq "PLAN"}{if $row2[10] eq t}🔵️{/if}{/if}{/if}</td>
   	{/if}
    <td><a href='{$smarty.const.URL}?exhibition={$row2[7]}'>{$row2[0]}</a> {if $row2[12] eq t}📕{/if} {if $row2[13] eq t}🎧{/if}</td>
    <td><a href="{$smarty.const.URL}?museum={$row2[8]}">{if $is_smartphone eq "FALSE"}<nobr>{/if}{$row2[1]}{if $is_smartphone eq "FALSE"}</nobr>{/if}</a></td>
    {if $year eq null}
    	<td>{if $row2[9] eq t}✅{/if}</td>
    {/if}
    <td>{if $is_smartphone eq "FALSE"}<nobr>{/if}{$row2[2]}〜{$row2[3]}{if $is_smartphone eq "FALSE"}</nobr>{/if}</td>
    <td><b>{$row2[5]} {$row2[15]} {$row2[14]}</b>
    	{if $expedition eq ""}{if $row2[20] neq null}<a href="{$smarty.const.URL}?expedition={$row2[19]}"><b>{$row2[20]}</b></a>{/if}{/if} {$row2[21]}
    </td>
    <td>{if $row2[17] neq null}<a href="{$row2[17]}" target="_blank">HP</a>{/if}</td>
    <td>{if $row2[6] neq null}<a href="{$row2[6]}" target="_blank">HP</a>{/if}</td>
    <td align="right">{$smarty.foreach.loop.iteration}</td>
    </tr>
    {/if}
    {/foreach}
    </table>
{/if}

{* 美術館一覧 *}
{if $data != ""}
    <table>
    {foreach from=$data key=key item=row name=loop}
    {if $row != null}
    <tr bgcolor="{cycle values="#FFF0F5,#F0FFFF"}"><!-- ,#FFFFCC -->
    {* <td align="right">{$smarty.foreach.loop.iteration}</td> *}
    <td align="right">{$row[0]}</td>
    <td><a href="{$smarty.const.URL}?museum={$row[0]}">{$row[1]} <!-- <font color="blue">{$row[2]}</font> --></a></td>
    <td>{if $row[13] eq t}✅{/if}</td>
    <td>{$row[23]}</td>
    {if $row[21] neq null}
        <td colspan=7><b><font color="red">{if $row[22] neq null}休館中 ({$row[21]}～{$row[22]}){else}閉館 ({$row[21]}){/if}</font></b></td>
    {else if $row[10] neq null}
        <td colspan=7><font color="red">{$row[10]}</font></td>
    {else}
        <td>{$row[3]}〜{$row[4]}
        {if $row[5] neq null}
            ({$row[6]}〜{$row[5]})
        {/if}
        </td>
        <td>{$row[7]}</td>
        <!-- <td>{$row[9]}</td> -->
 	    <td>{$row[20]}</td>
 	    <td>{$row[17]}</td>
	    <td>{$row[18]}</td>
	    <td>{$row[19]}</td>
        <td>{$row[12]}</td>
    {/if}
    <td>{if $row[8] neq null}<a href="{$row[8]}" target="_blank"><font color="blue">HP</font>{/if}</td>
    <!-- <td>{if $is_smartphone eq t} <a href="comgooglemaps://?q={$row[1]}">MAP</a>{else} <a href="http://www.google.co.jp/maps?q={$row[1]}" target="_blank">MAP</a>{/if}</td> -->
    <td>{if $row[15] neq null}<a href="{$row[15]}" target="_blank"><font color="green">SCH</font>{/if}</td>
    <td>{if $row[14] neq null}<a href="{$row[14]}" target="_blank">MAP</a>{/if}</td>
    {* <td align="right">{$row[0]}</td> *}
    </tr>
    {/if}{/foreach}
    </table>
{/if}
<br>
    <br>
    <br><br>
<a href="ex.php" target="_blank">exhibition</a>&nbsp;&nbsp;&nbsp;
<a href="http://www.museum.or.jp/modules/jyunkai/" target="_blank">Internet Museum</a>
<br><br>
    <b>Powered by</b><br>
    {php_uname()}<br> {$dbver},<br> PHP {phpversion()},<br>Smarty {$smarty.version}<br>
    <br>
</div>
<!--
<div id="back-top"><a href="#top"><img src="up-trans.png"></a></div>
-->
</body>
</html>
