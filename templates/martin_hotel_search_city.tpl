<table width="500" border="0" cellspacing="5" cellpadding="5" style="border:#666666 1px solid; background:#FFFFFF;">
    <tr>
        <td colspan="8" align="right"><a href="javascript:void(0);"
                                         onclick="jQuery('input[name=HotelAddress]').val('');jQuery('#HotelAddress').val('');jQuery('#selectAddress').hide();">不限位置</a>
        </td>
    </tr>
    <tr>
        <td colspan="8" class="gray" align="center"
            style="border-bottom:#CCCCCC 1px dotted; border-top:#CCCCCC 1px dotted;">行政区
        </td>
    </tr>
    <tr>
        <{assign var=i value=0}>
        <{foreach from=$rows name=row item=row}>
        <{if $row.1 eq 1}>
        <{assign var=i value=$i+1}>
        <td><a href="javascript:void(0);"
               onclick="InsertCity('<{$row.city_id}>','<{$row.city_name}>')"><{$row.city_name}></a></td>
        <{if $i is div by 8}></tr>
    <tr><{/if}>
        <{/if}>
        <{/foreach}>
    </tr>
    <tr>
        <td colspan="8" class="gray" align="center"
            style="border-bottom:#CCCCCC 1px dotted; border-top:#CCCCCC 1px dotted;">商业区域
        </td>
    </tr>
    <tr>
        <{assign var=i value=0}>
        <{foreach from=$rows name=row item=row}>
        <{if $row.1 eq 2}>
        <{assign var=i value=$i+1}>
        <td><a href="javascript:void(0);"
               onclick="InsertCity('<{$row.city_id}>','<{$row.city_name}>')"><{$row.city_name}></a></td>
        <{if $i is div by 8}></tr>
    <tr><{/if}>
        <{/if}>
        <{/foreach}>
    </tr>
</table>
