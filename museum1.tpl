<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{if $title != ""}{$title} - MUSEUM{else}MUSEUM{/if}</title>
    <link type="text/css" rel="stylesheet" href="museum.css" />
    <link rel="shortcut icon" href="../favicon.ico" />
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
    <form action="./index.php" method="post" class="museum" name="museum" id="museum" enctype="multipart/form-data">
        <table><tr>
        <td><button id="fukuoka" name="fukuoka" />{$label[0]}</button></td>
        <td><button id="kyushu" name="kyushu" />{$label[1]}</button></td>
        <td><button name="chugoku" />{$label[2]}</button></td>
        <td><button name="kansai" />{$label[3]}</button></td>
        <td><button name="tokai" />{$label[4]}</button></td>
        <td><button name="tokyo" />{$label[5]}</button></td>
        <td><button name="kanto" />{$label[6]}</button></td>
        <td><button name="tohoku" />{$label[7]}</button></td>
        </tr>
        <tr>
        <td><button id="fukuoka" name="fukuoka2" />{$label[0]}</button></td>
        <td><button id="kyushu" name="kyushu2" />{$label[1]}</button></td>
        <td><button name="chugoku2" />{$label[2]}</button></td>
        <td><button name="kansai2" />{$label[3]}</button></td>
        <td><button name="tokai2" />{$label[4]}</button></td>
        <td><button name="tokyo2" />{$label[5]}</button></td>
        <td><button name="kanto2" />{$label[6]}</button></td>
        <td><button name="tohoku2" />{$label[7]}</button></td>
        </tr>
        <tr>
        <td><button name="go">{$label[8]}</button></td>
        <td colspan=3>{html_options name=year options=$year_options selected=$year}</td>
        <td></td>
        <td></td>
        <td></td>
        <!--<td></td>-->
        <td><button name="now" />{$label[9]}</button></td>
        </tr>
        </table>
</form>
<BR>

{if $museum != ""}
    <h1>{$museum[0]}</h1>
    <h3>{$museum[8]}</h3>
    <table>
    <tr><td align=right>Webサイト</td><td><a href="{$museum[7]}" target="_new">{$museum[7]}</a></td></tr>
    <tr><td align=right>開館時間</td><td>{$museum[1]}～{$museum[2]}</td></tr>
    <tr><td align=right>休館日</td><td>{$museum[3]}</td></tr>
    <tr><td align=right>割引</td><td>
		{if $museum[4] eq "JAF"}<img src="./jaf.png" height=18 alt="JAF" >{elseif $museum[4] eq "交通系IC"}<img src="./ic.gif" height=18 alt="IC" >{else}{$museum[4]}{/if}
		</td></tr>
    <tr><td align=right>支払</td><td>
    {if $museum[5] != null}
    	{if $museum[5] eq "交通系IC"}<img src="./ic.gif" height=18 alt="IC" >
    	{elseif $museum[5] eq "VISA"}<img src="./visa.jpg" height=14 alt="VISA" >
    	{elseif $museum[5] eq "1"}<img src="./visa.jpg" height=14 alt="VISA" > <img src="./master.png" height=18 alt="MasterCard" > <img src="./jcb.gif" height=14 alt="JCB" > <img src="./ic.gif" height=18 alt="IC" >
    	{else}{$museum[5]}{/if}
	{/if}
	</td></tr>
    <tr><td align=right>年間スケジュール</td><td><a href="{$museum[9]}" target="_new">{$museum[9]}</a></td></tr>
    <tr><td align=right>備考</td><td>{$museum[6]}</td></tr>
    </table>
    <br>
{/if}

<h1>{$title}</h1><br>

{*
{if $year != ""}
    <h1>{$year}</h1>
    <br>
{elseif $exhibition != ""}<h1>{$exhibition}</h1><br>
{/if}
*}

{if $expedition != ""}
    <table>
        <tr><!-- <td align=right>宿泊</td> --><td>{$expedition[0]}</td></tr>
    </table>    
    <br>
{/if}

{if $exhibit != ""}
    <table>
    {foreach from=$exhibit key=key item=row2 name=loop}
    {if $row2 != null}
    <tr bgcolor="{cycle values="#FFF0F5,#F0FFFF"}">
    <td align="right">{$smarty.foreach.loop.iteration}</td>
    <!--<td>{if $row2[10] eq t}☆{/if}</td>-->
    <td align="right">{$row2[18]}</td>
    <td align="right">{if $row2[4] neq ""}{$row2[4]}{else}{if $row2[10] eq t}🔵️{/if}{/if}</td>
    <td><a href="https://www.hojomasaki.com/museuxm/index.php?exhibition={$row2[7]}">{$row2[0]}</a> {if $row2[12] eq t}📕{/if} {if $row2[13] eq t}🎧{/if}</td>
    <td><a href="https://www.hojomasaki.com/museuxm/index.php?museum={$row2[8]}">{$row2[1]}</a></td>
    <td>{if $row2[9] eq t}✅{/if}</td>
    <td>{$row2[2]}〜{$row2[3]}</td>
    <!--<td><b>{$row2[4]}</b></td>-->
    <td><b>{$row2[5]} {$row2[15]} {$row2[14]}</b>
    	{if $row2[16] neq null}<a href="https://www.hojomasaki.com/museuxm/index.php?expedition={$row2[19]}"><b>
        {if $row2[20] neq null}{$row2[20]}{else}{$row2[16]}{/if}</b></a>{/if}
    </td>
    <td>{if $row2[17] neq null}<a href="{$row2[17]}" target="new">HP</a>{/if}</td>
    <td>{if $row2[6] neq null}<a href="{$row2[6]}" target="new">HP</a>{/if}</td>
    <td align="right">{$row2[7]}</td><td align="right">{$row2[8]}</td>
    </tr>
    {/if}
    {/foreach}
    </table>
{/if}

{if $data != ""}
    <table>
    {foreach from=$data key=key item=row name=loop}
    {if $row != null}
    <tr bgcolor="{cycle values="#FFF0F5,#F0FFFF"}"><!-- ,#FFFFCC -->
    <td align="right">{$smarty.foreach.loop.iteration}</td>
    <td><a href="https://www.hojomasaki.com/museuxm/index.php?museum={$row[0]}">{$row[1]} <!-- <font color="blue">{$row[2]}</font> --></a></td>
    {if $row[10] neq null}
        <td></td>
        <td colspan=5><font color="red">{$row[10]}</font></td>
    {else}
        <td>{if $row[13] eq t}✅{/if}</td>
        <td>{$row[3]}〜{$row[4]}
        {if $row[5] neq null}
            ({$row[6]}〜{$row[5]})
        {/if}
        </td>
        <td>{$row[7]}</td>
        <!-- <td>{$row[9]}</td> -->
        <td>{if $row[9] eq "JAF"}<img src="./jaf.png" height=18 alt="JAF" >{elseif $row[9] eq "交通系IC"}<img src="./ic.gif" height=18 alt="IC" >{else}{$row[9]}{/if}</td>
    <td>
    	{if $row[11] eq "交通系IC"}<img src="./ic.gif" height=18 alt="IC" >
    	{elseif $row[11] eq "VISA"}<img src="./visa.jpg" height=12 alt="VISA" >
    	{elseif $row[11] eq "1"}<img src="./visa.jpg" height=14 alt="VISA" > <img src="./master.png" height=18 alt="MasterCard" > <img src="./jcb.gif" height=14 alt="JCB" > <img src="./ic.gif" height=18 alt="IC" >
    	{else}{$row[11]}{/if}</td>
    <td>{$row[12]}</td>
    {/if}
    <td>{if $row[8] neq null}<a href="{$row[8]}" target="_blank"><font color="blue">HP</font>{/if}</td>
    <!-- <td>{if $is_smartphone eq t} <a href="comgooglemaps://?q={$row[1]}">MAP</a>{else} <a href="http://www.google.co.jp/maps?q={$row[1]}" target="_new">MAP</a>{/if}</td> -->
    <td>{if $row[15] neq null}<a href="{$row[15]}" target="_blank"><font color="green">SCH</font>{/if}</td>
    <td>{if $row[14] neq null}<a href="{$row[14]}" target="_new">MAP</a>{/if}</td>
    <td align="right">{$row[0]}</td>
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
