<?php
function build_param_values($params){
 $output = array();
 $a = explode(",",$params);
 if (count($a)>0) {
  foreach($a as $l){
   $l = trim($l);
   if ($l) {
    $a1 = preg_split("/[\|\/]/",$l);
    $output[$a1[0]] = $a1[1];
   }
  }
 }
 return $output;
}
ob_start();
include ('./admin/_inc/config.php');
if ($_GET['act']=="clean") { 
  unset($_SESSION[basket]);
  header("Location: basket.php");
  exit;
}
$pname="basket";
$general=get_item_byfield("txt_pages","page_name",$pname);
$general['price_list']=get_text("price_file");
if ($general['price_list']) $general['price_list']=$manageable_images_dirweb.$general['price_list'];
get_categories($general);
$somedata = $general;
$db->query("select count(*) from txt_services");
$general['count'] = db_fetch_rec(0);
if ($_POST['ordered']>0 && is_array($_POST[p])) {
 foreach ($_POST as $name=>$value) $general['form'][$name]=$value;
 $general['seo_page_title']="Заказ отправлен";
 $headers= "From: ".get_html($general['form']['fullname'])." <".get_html($general['form']['email']).">\n";
 $headers.="X-Mailer: Orders Auto-sender System\n";
 //$headers.="Content-Type: text/plain; charset=windows-1251\n";
 $headers.="Content-Type: text/plain; charset=utf-8\n";
 $body=date("d.m.Y H:i:s",mktime(date("H")+5,date("i"),date("s"),date("n"),date("j"),date("Y")))."\r\nЗаказ №:###\r\nДанные заказа: \r\n";
 $products = "";
 $i=1;
 $total_qty=$total_sum=0;
 $customVar = [];
 foreach($_POST[pid] as $row=>$item_id) {
  $item_qty = $_POST[p][$row];
  $item_clr = $_POST[clr][$row];
  $item_price = $_POST[prc][$row];
  $item_params = $_POST[prm][$row] ? unserialize(base64_decode($_POST[prm][$row])) : array();
  if ($item_qty>0) {
   $item=get_item_byid("txt_services",$item_id);
   if (count($item)) {
    $product_color = get_item_byid('txt_products_colors',$item_clr);
    $product_category = get_item_byid('txt_products_colors',$item_clr);
    $color = get_item_byid('txt_colors',$product_color['color_id']);
    $total_qty+=$item_qty;
    $total=$item_qty*$item_price;
    //$body.=$i.". ".$item_qty." x ".$item['title']." (Цвет: ".$_POST[cl][$row].((is_array($item_params) && count($item_params)) ? ", ".implode(", ", $item_params) : "" ).") = ".$total." руб.\r\n";
    $body.=$i.". ".$item_qty." x ".$item['title']." ".($color['id']?"(Выбранный Цвет: ".$color['title'].")":"").((is_array($item_params) && count($item_params)) ? "(".implode(", ", $item_params).")" : "" )." = ".$total." руб.\r\n";
    $products .= ($products?",":"").'{"id": "'.$item['id'].'","name": "'.get_html($item['title']).'", "price": '.$total.', "brand": "Ofis 54", "variant": "'.($color['id']?"(Выбранный Цвет: ".$color['title'].")":"").((is_array($item_params) && count($item_params)) ? "(".implode(", ", $item_params).")" : "" ).'", "quantity": '.$item_qty.'}';
    $total_sum+=$total;
    $i++;
   }
  }
 }
 $body.="-------------------------------\r\nИтого: ".$total_qty." шт. на ".$total_sum." руб.\r\n";
 $db->query("INSERT INTO `txt_orders` (`regtime`, `fullname`, `address`, `telephone`, `email`, `comments`)
                               VALUES ('".time()."', '".addslashes($general['form']['fullname'])."', '".addslashes($general['form']['address'])."', '".addslashes($general['form']['telephone'])."', '".addslashes($general['form']['email'])."', '".addslashes($general['form']['comments'])."');");
 $order_id = $db->last_insert_id();
 $body = str_replace(":###", ": ".$order_id, $body);
 $db->query("UPDATE txt_orders set `order`='".addslashes($body)."' where id='".addslashes($order_id)."' limit 1;");
 $body.="\r\nИмя: ".get_html($general['form']['fullname'])."\r\nТелефон: ".get_html($general['form']['telephone'])."\r\nАдрес: ".get_html($general['form']['address'])."\r\nEmail: ".get_html($general['form']['email'])."\r\nКомментарии:\r\n".get_html($general['form']['comments']);
 $mail_to=get_text("Email_email_address");
 $mail_subj=get_text("Email_email_subject");
 if (!trim($mail_subj)) $mail_subj="Заказ с сайта";
 @mail(get_html($mail_to),"=?utf-8?B?".base64_encode(get_html($mail_subj))."?=",$body,$headers);
 @mail("mambas@mail.ru","=?utf-8?B?".base64_encode(get_html($mail_subj))."?=",$body,$headers);
 $headers= "From: ofis54.ru <".get_html(get_text("homepage_email")).">\nX-Mailer: Ofis54 Orders Auto-sender System\nContent-Type: text/plain; charset=utf-8\n";
 @mail(get_html($general['form']['email']),"=?utf-8?B?".base64_encode("Вы сделали заказ на сайте http://".$_SERVER[HTTP_HOST])."?=",$body,$headers);
 $general['sent']=1;
 $general[page_text] = get_text("Email_thankyou");
 unset($_SESSION[basket]);
 $_SESSION['lastorder']['id'] = $order_id;
 $_SESSION['lastorder']['products'] = $products;
 ob_clean();
 header("Location: /thankyou");
 exit;
} else {
 $general['seo_page_title']="Корзина";
 if ($_POST['product_id'] && $_POST['qty']>0) { 
  $_SESSION[basket][items][$_POST['product_id']][$_POST['color']]+=$_POST['qty'];
  header("Location: basket.php");
  exit;
 }
}
if (!$general[page_title_ext]) $general[page_title_ext]="Оформление заказа";
$general['active'] = 'basket';
?><? include('_head_hp_.php'); ?>
<? include('_topmenu_hp_.php'); ?>
<br>
<style>
    h1 { font-size: 36px; margin: 10px 0 10px 0; }
    #btn_clean_basket { float:right; margin-right: 8.5%; margin-top: 11px;}
    .req{ float: right; margin-top: 8px;}
</style>
<div class="container-fluid bg-3 text-center pageintro">
  <div class="row">
   <div class="col-sm-1"> 
   </div>
   <div class="col-sm-10">
     <h1><?=get_html($general[page_title_ext])?></h1>
     <?=$general[page_text]?>
     <? if ($general['sent']) { ?>
     <script>
        dataLayer.push({
            "ecommerce": {
                "purchase": {
                    "actionField": {
                        "id" : "<?=get_html($order_id)?>"
                    },
                    "products": [<?=$products?>]
                }
            }
        });
     </script>
     <? } ?>
   </div>
   <div class="col-sm-1"> 
   </div>
  </div>
</div>
<? if (!$general['sent']) { ?>
<div class="container-fluid">
<? if ($_SESSION[basket][total]>0 && is_array($_SESSION[basket][items])) { ?>
  <div class="row">
   <div class="col-sm-1"> 
   </div>
   <div class="col-sm-11 text-left"> 
    <button class="btn btn-warning" id="btn_clean_basket" onclick="top.location.href='basket.php?act=clean'">Очистить корзину</button>
    <h3>Вы выбрали:</h3>
   </div>
  </div>
  <div class="row table_borders table_header">
   <div class="col-sm-1"> 
   </div>
   <div class="col-sm-3 header text-center"> 
    Наименование
   </div>
   <div class="col-sm-2 header text-center"> 
    Фото
   </div>
   <div class="col-sm-1 header text-center"> 
    Цена
   </div>
   <div class="col-sm-2 header text-center"> 
    Количество
   </div>
   <div class="col-sm-2 header text-center"> 
    Стоимость
   </div>
   <div class="col-sm-1"> 
   </div>
  </div>
<form method="post" action="" onSubmit="return check_data(this);" id="basket_form">
<?
$_SESSION[basket][qty_total]=$_SESSION[basket][total]=0;
$r=1;
foreach ($_SESSION[basket][items] as $b=>$basket_product) {
 $product_params = array();
 $item = get_item_byid('txt_services',$basket_product['product']['id']);
 $product_color = get_item_byid('txt_products_colors',$basket_product['product']['product_color_id']);
 $color = get_item_byid('txt_colors',$product_color['color_id']);
 $params = preg_split("/([\r\n])+/is", trim($item['intro']));
 foreach ($params as $line) {
  $tmparr1=explode("::",trim($line),2);
  if ($tmparr1[0] && $tmparr1[1]) $product_params[] = array('title'=>$tmparr1[0],'value'=>$tmparr1[1],'values'=>build_param_values($tmparr1[1]));
 }
 if (count($item)) {
   $_SESSION[basket][qty_total]+=$basket_product['qty'];
   $product_total=$basket_product['qty']*$basket_product['price'];
   $_SESSION[basket][total]+=$product_total;
   $db->query("select image from txt_photos where parent_id='".addslashes($basket_product['product']['id'])."'".($product_color['photo_id']?" and id='".addslashes($product_color['photo_id'])."'":"")." order by ord limit 1");
   $item['image'] = db_fetch_rec(0);
?>
  <div class="row table_borders line_<?=$r?>">
   <div class="col-sm-1"> 
   </div>
   <div class="col-sm-3 cell text-center"> 
    <?=get_html($item['title'])?>
    (
     <?=get_html($basket_product['product']['color']?"Цвет: ".$basket_product['product']['color']:"")?>
     <?=get_html($color['id']?"Выбранный Цвет: ".$color['title']:"")?>
     <?
      $basket_product['params'] = array();
      $hs = 0;
      for($p=0;$p<10;$p++) {
       if ($basket_product['product']['p'.$p]) {
        $basket_product['params'][] = trim($product_params[$p]['title'].": ".$basket_product['product']['p'.$p]);
        echo ($hs?", ":"").get_html(($basket_product['product']['color']?", ":"").$product_params[$p]['title'].": ".$basket_product['product']['p'.$p]);
        $hs++;
       }
      }
     ?>
    )
   </div>
   <div class="col-sm-2 cell text-center"> 
    <img src="<?=get_html($manageable_images_dirweb.SMALLPREFIX.$item['image'])?>">
   </div>
   <div class="col-sm-1 cell text-center"> 
    <span><?=get_html($basket_product['price'])?></span>
   </div>
   <div class="col-sm-2 cell text-center"> 
		<br />
		<a href="javascript: recalc('-','<?=$r?>');" class="cartrecalc" style="font-size: 18px"><i class="more-less fa fa-minus"></i></a>
		<input type="text" id="p<?=$r?>" name="p[<?=get_html($r)?>]" value="<?=get_html($basket_product['qty'])?>" size="7" class="inputcart" readonly="readonly" style="width: 60px; display: inline-block; text-align: center;">
		<a href="javascript: recalc('+','<?=$r?>');" class="cartrecalc"><i class="more-less fa fa-plus"></i></a>
		<input type="hidden" id="pid<?=$r?>" name="pid[<?=get_html($r)?>]" value="<?=$basket_product['product']['id']?>">
		<input type="hidden" id="prc<?=$r?>" name="prc[<?=get_html($r)?>]" value="<?=get_html($basket_product['price'])?>">
		<input type="hidden" id="clr<?=$r?>" name="cl[<?=get_html($r)?>]" value="<?=get_html($basket_product['product']['product_color_id'])?>">
		<input type="hidden" id="prm<?=$r?>" name="prm[<?=get_html($r)?>]" value="<?=base64_encode(serialize($basket_product['params']))?>">
   </div>
   <div class="col-sm-2 cell text-center"> 
    <a href="javascript: remove_row(<?=$r?>);" title="Удалить позицию" style="float: right; color: #000000;"><i class="more-less fa fa-remove"></i></a>
    <span id="pr<?=$r?>"><?=get_html($product_total)?></span>
   </div>
   <div class="col-sm-1"> 
   </div>
  </div>
<?
   $r++;
 }
}
?>
  <div class="row table_borders">
   <div class="col-sm-1"> 
   </div>
   <div class="col-sm-3 cell table_total"> 
    <strong>Итого</strong>
   </div>
   <div class="col-sm-2 cell"> 
    
   </div>
   <div class="col-sm-1 cell"> 
    
   </div>
   <div class="col-sm-2 cell text-center"> 
    <span id="qty_total" style="font-weight: bold;"><?=get_html($_SESSION[basket][qty_total])?></span>
   </div>
   <div class="col-sm-2 cell text-center"> 
    <span id="pr_total" style="font-weight: bold;"><?=get_html((int)$_SESSION[basket][total])?></span> <b>руб.</b>
   </div>
   <div class="col-sm-1"> 
   </div>
  </div>
<script type="text/javascript">
function check_data(df) {
 // if (!df.fullname.value || df.fullname.value==df.fullname.defaultValue || !df.address.value || df.address.value==df.address.defaultValue || !df.telephone.value || df.telephone.value==df.telephone.defaultValue || !df.email.value || df.email.value==df.email.defaultValue || !df.comments.value) {
 if (!df.fullname.value || df.fullname.value==df.fullname.defaultValue || !df.telephone.value || df.telephone.value==df.telephone.defaultValue || !df.email.value || df.email.value==df.email.defaultValue) {
  alert("Заполните поля, как минимум отмеченные *, для оформления заказа.");
  return false;
 }
 return true;
}
function remove_row(id){
 if (confirm("Вы уверены?")) {
  var qty = parseInt($("#p"+id).val());
  if (!qty) {
   $(".line_"+id).remove();
  } else {
   if (qty > 1) {
    for(var i=qty;i>1;i--) {
     recalc('-',id);
    }
   }
   $("#p"+id).val('1');
   recalc('-',id);
   $(".line_"+id).remove();
  }
 }
}
function recalc(way,id){
 var old_total_price	= parseInt($("#pr_total").text());
 var old_total_qty	= parseInt($("#qty_total").text());
 var qty 		= parseInt($("#p"+id).val());
 var price 		= parseInt($("#prc"+id).val());
 var price_total 	= parseInt($("#pr"+id).text());
 if (way == '-') {
  if (qty>0) {
   qty--;
   price_total = price*qty;
   old_total_price -= price;
   old_total_qty--;
  }
 } else {
  qty++;
  price_total = price*qty;
  old_total_price += price;
  old_total_qty++;
 }
 $("#p"+id).val(qty);
 $("#pr"+id).text(""+price_total);
 $("#pr_total").text(""+old_total_price);
 $("#qty_total").text(""+old_total_qty);
}
</script>
  <div class="row">
   <div class="col-sm-1"> 
   </div>
   <div class="col-sm-5 text-left">
    <h3>Укажите имя, адрес, контакты получателя</h3>
    <div class="form-group">
      <label for="fn" class="col-2 col-form-label">Имя <req>*</req></label>
      <div class="col-10">
        <input class="form-control" type="text" id="fn" name="fullname" placeholder="ИМЯ" />
      </div>
    </div>
    <div class="form-group">
      <label for="tp" class="col-2 col-form-label">Контактный телефон <req>*</req></label>
      <div class="col-10">
        <input class="form-control" type="tel" id="tp" name="telephone" placeholder="Контактный телефон">
      </div>
    </div>
    <div class="form-group">
      <label for="ad" class="col-2 col-form-label">Адрес доставки</label>
      <div class="col-10">
        <input class="form-control" type="text" id="ad" name="address" placeholder="Адрес доставки" />
      </div>
    </div>
    <div class="form-group">
      <label for="em" class="col-2 col-form-label">E-mail <req>*</req></label>
      <div class="col-10">
        <input class="form-control" type="email" id="em" name="email" placeholder="e-mail" />
      </div>
    </div>
    <div class="form-group">
      <label for="tx">Комментарии</label>
      <textarea class="form-control" name="comments" id="tx" rows="3" placeholder="Комментарии"></textarea>
      <span class="req">* - обязательные поля</span>
      <br />
      <button type="submit" class="btn btn-primary">Оформить заказ</button>
    </div>
    <input type="hidden" name="ordered" value="<?=get_html($_SESSION[basket][qty_total])?>">
   </div>
   <div class="col-sm-1"> 
   </div>
  </div>
  </form>
<? } else { ?>
	<center><h3>Корзина пуста</h3></center>
<? } ?>
</div>
<? } ?>
<br><br>
<? include('_footer_hp_.php'); ?>
</body>
</html>