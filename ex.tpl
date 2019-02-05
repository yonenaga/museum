<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>EXHIBITION</title>
    <link type="text/css" rel="stylesheet" href="museum.css" />
    <link rel="shortcut icon" href="../favicon.ico" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body>

<div align=center>
    <form action="./ex.php" method="post" enctype="multipart/form-data">
        <input type="text" name="searchstr" placeholder="" size=30 maxlength=100 value={if $input != ""}{$input}{else}""{/if} /> <button name="keyword" />検索</button>
    </form>
<BR>
{if $data != ""}
    <table>
    {foreach from=$data key=key item=row name=loop}
    {if $row != null}
    <tr bgcolor="{cycle values="#FFF0F5,#F0FFFF"}">
    <td align="right">{$row[0]}</td>
    <td><a href="{$smarty.const.URL}?exhibition={$row[0]}">{$row[1]}  {if $row[5] eq t}📕{/if}</td>
    <!--td><a href="http://www.google.co.jp/?q={$row[1]}">Google</a></td-->
    <td align="right">{if $row[4] neq null}<a href="{$row[4]}" target="_blank">HP</a>{/if}</td>
    <td align="right">{$row[2]}</td>
    <td align="right">{$row[3]}</td>
    </tr>
    {/if}{/foreach}
    </table>
{/if}
</div>
</body>
</html>
