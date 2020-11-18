{ if !isset($order_by) }{ assign var=order_by value='' }{ /if }

{ capture assign=order_by }{ $order_by|regex_replace:"/\s+/":" " }{ /capture }
{ capture assign=item_desc }{ $item_name } desc{ /capture }
{ capture assign=item_asc }{ $item_name } asc{ /capture }

{ if $order_by eq $item_asc  }
    <a class=tbl-hdr href="{php}echo base_url(); {/php}admin/view/{$current_model}/order_by={ $item_name }%20desc">{ $item_title }</a>&nbsp;<img src="{php} echo base_url();{/php}images/down.jpg" width="9" height="11" border="0">
{ elseif $order_by eq $item_desc }
    <a class=tbl-hdr href="{php}echo base_url(); {/php}admin/view/{$current_model}/order_by={ $item_name }%20asc">{ $item_title }</a>&nbsp;<img src="{php} echo base_url();{/php}images/up.jpg" width="9" height="11" border="0">
{ else }
    <a class=tbl-hdr href="{php}echo base_url(); {/php}admin/view/{$current_model}/order_by={ $item_name }%20asc">{ $item_title }</a>
{ /if }

