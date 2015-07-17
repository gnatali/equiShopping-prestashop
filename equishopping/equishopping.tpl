<script src="http://tracking.equishopping.com/{$seller_id}" type="text/javascript"></script>
<script type="text/javascript">
// <![CDATA[
var t = new equiTracking();
t.seller_id = "{$seller_id}";
t.order_id = "{$order_id}";
t.total_exc_vat = "{$total_exc_vat}";
t.total_inc_vat = "{$total_inc_vat}";
t.vat = "{$vat}";
t.shipping = "{$shipping}";
t.currency = "{$currency}";
t.cli_id = "{$cli_id}";
t.cli_email = "{$cli_email|escape:javascript}";
t.cli_firstname = "{$cli_firstname|escape:javascript}";
t.cli_lastname = "{$cli_lastname|escape:javascript}";
t.cli_city = "{$cli_city|escape:javascript}";
t.cli_country = "{$cli_country|escape:javascript}";
t.payment_method = "{$payment_method|escape:javascript}";
t.equiNewOrder();
{foreach $products as $p}
t.item_price_exc_vat = "{$p.product_price}";
t.item_quantity = "{$p.product_quantity}";
t.item_sku = "{$p.product_id}";
t.item_name = "{$p.product_name|escape:javascript}";
t.equiAddItem();
{/foreach}

t.equiValid();
// ]]>
</script>
<noscript><img src="http://tracking.equishopping.com/light-process.php?seller_id={$seller_id}&order_id={$order_id}&price={$total_exc_vat}" /></noscript>
