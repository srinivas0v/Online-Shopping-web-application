
<?php
// Start the session
session_start();
$x1;
$_SESSION['Tcost'] = 0;
?>


<html>
<head>
  <style>

#display_item{
    width:800px;
    table-layout: fixed;
}
table {
    border-collapse: collapse;
    width:50%;
    font-family: Arial, Verdana, sans-serif;
    line-height: 1em;
    color: #665544;
    text-align: justify; 
    font-size: 12px;
}

 #display_item th{
  text-align: center; 
}

 td{
text-align: left;
vertical-align: top;
}

tr:nth-child(even){background-color: #f2f2f2}

th {
    background-color: #4CAF50;
    color: white;
}
#display_item tr:hover {background-color: d6d4f7}

#cart{
    width:400px;
    table-layout: fixed;
}

#pricetag{
    text-align:right;
}
#productname{
    font-weight:bold;
}
#Totalcost{
    text-align:right;
    font-weight:bold;
    color:green;
    font-size: 15px;
}

#cartheader th{
text-align: center; 
}

#deletetag{
    text-align:right;
}
</style>
<title>Buy Products</title></head>
<body>

<?php
  function setVariables() {
    global $x1;
    $ctg = $_GET['category'];
    $q = urlencode($_GET['query']);
    if(!empty($q))
    {
        $xstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&categoryId='.$ctg.'&keyword='.$q.'&numItems=20');
        $x1 = new SimpleXMLElement($xstr);

    }
  }

    function InitializeCart()
    {
        $didntfind=0;
        $_SESSION['buyid'] = '';
        $b_id = $_GET['buy'];
        $_SESSION['buyid'] = $b_id;
        for($k=0;$k<count($_SESSION['name']);$k++)
        {
            if(empty($_SESSION['cart']) && $_SESSION['sid'][$k] == $b_id)
            {
                $_SESSION['cart'][] = $_SESSION['sid'][$k];
                $_SESSION['cart'][] = $_SESSION['name'][$k];
                $_SESSION['cart'][] = $_SESSION['producturl'][$k];
                $_SESSION['cart'][] = $_SESSION['image'][$k];
                $_SESSION['cart'][] = $_SESSION['cost'][$k];
                $_SESSION['sid'] = '';
                $_SESSION['name'] = '';
                $_SESSION['image'] = '';
                $_SESSION['cost'] = '';
                $_SESSION['producturl'] = '';
                break;
            }
            for($p=0;!empty($_SESSION['cart']) && $p<count($_SESSION['cart']);$p=$p+5)
            {
                if($_SESSION['cart'][$p] == $b_id)
                {
                    $didntfind = 0;
                    break;
                }else{
                    $didntfind = 1;
                }
            }
            if($didntfind == 1 && $_SESSION['sid'][$k] == $b_id)
            {
                $_SESSION['cart'][] = $_SESSION['sid'][$k];
                $_SESSION['cart'][] = $_SESSION['name'][$k];
                $_SESSION['cart'][] = $_SESSION['producturl'][$k];
                $_SESSION['cart'][] = $_SESSION['image'][$k];
                $_SESSION['cart'][] = $_SESSION['cost'][$k];
                $_SESSION['sid'] = '';
                $_SESSION['name'] = '';
                $_SESSION['image'] = '';
                $_SESSION['cost'] = '';
                $_SESSION['producturl'] = '';
                break;
            }
        }
    }

  function removeItem()
  {
        $deleteid = $_GET['delete'];
        $delete = array();
        for($k=0;!empty($_SESSION['cart']) && $k<count($_SESSION['cart']);$k++)
        {
             if($_SESSION['cart'][$k] == $deleteid)
            {
                unset($_SESSION['cart'][$k]);$k++;
                unset($_SESSION['cart'][$k]); $k++;
                unset($_SESSION['cart'][$k]); $k++;
                unset($_SESSION['cart'][$k]); $k++;
                unset($_SESSION['cart'][$k]); 
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            } 
        }   
  }

  function emptyCart()
  {
        session_unset(); 
        session_destroy(); 
  }
  ?>

<form action='buy.php' >
<fieldset>
  <legend>Find Products:</legend>
  Category:&nbsp;&nbsp;&nbsp;
 <select name='category'>
<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
$xstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=72&showAllDescendants=true');
$xml = new SimpleXMLElement($xstr);?>
<option value='<?php echo $xml->category['id'] ?>'><?php echo $xml->category->name ?></option>   
    <optgroup label=<?php echo $xml->category->name ?>
    <?php foreach($ctg->categories->category as $name){?>
        <option value='<?php echo $name['id'] ?>'><?php echo $name->name ?></option>
    <? }?>

<? foreach( $xml->category->categories->category as $ctg) { ?>
    <option value='<?= $ctg['id'] ?>'> <?= $ctg->name ?></option>
    <optgroup label='<?= $ctg->name ?>'>
    <?php foreach ( $ctg->categories->category as $ctg) { ?>
    <option value='<?= $ctg['id'] ?>'> <?= $ctg->name ?></option>
    <?php } ?>
    </optgroup>
<?php } ?>


  <?php

  if (isset($_GET['query'])) {
    setVariables();
  }
  
  if (isset($_GET['buy'])) {
    InitializeCart();
  }

  if(isset($_GET['clear'])) {
      emptyCart();
  }

  if(isset($_GET['delete'])) {
      removeItem();
  }

?>


  </select>&nbsp;&nbsp;&nbsp;&nbsp;
  Search Keywords:&nbsp;&nbsp;&nbsp;
  <input type='text' name='query' value=''>&nbsp;&nbsp;&nbsp;
  <input type='submit' value='Search'>
  </fieldset>
</form> 

<table>
<tr><td>


<table id='display_item'>
<?php
if(!empty($x1))
{?>
<tr>
<th>Product Image </th>
<th>Product Name </th>
<th>Product Description</th>
<th>Product Price</th>
</tr>
<?php
global $x1;
if(!empty($x1))
{
    foreach($x1->categories->category->items->product as $searchresults)
        {
        $_SESSION['name'][] = strval($searchresults->name);
        $_SESSION['image'][] = strval($searchresults->images->image->sourceURL);
        $_SESSION['cost'][] = strval($searchresults->minPrice);
        $_SESSION['sid'][] = strval($searchresults['id']);
        $_SESSION['producturl'][] = strval($searchresults->productOffersURL);
        ?><tr>
        <td> <a href='buy.php?buy=<?php echo $searchresults['id']; ?> '> <img src=' <?php echo $searchresults->images->image->sourceURL; ?> '/></a> </td>
        <td> <?php echo $searchresults->name; ?> </td>
        <td> <?php echo $searchresults->fullDescription; ?> </td>
        <td style='text-align:right;font-weight:bold'> <?php echo $searchresults->minPrice; ?> </td>
        </tr>
        <?php
        }
 }
 }?>
</table>

</td>

<td >
<table id= 'cart'>
<tr style='text-align:center'>
<th id='cartheader'> Shopping Basket </th>
</tr>
<tr><td>
<?php
if(isset($_SESSION['cart'])>0)
 {
    for($p=0;!empty($_SESSION['cart']) && $p<count($_SESSION['cart']);$p++)
    {
      //  print_r($_SESSION['cart']);
    ?>
        <div id='deletetag'><a href='buy.php?delete=<?php echo $_SESSION['cart'][$p];$p++;?>'>delete</a></div>
        <div id='productname'><?php echo $_SESSION['cart'][$p]; $p++;?></div> 
        <a href='<?php echo $_SESSION['cart'][$p];$p++; ?>'><img src='<?php echo $_SESSION['cart'][$p]; $p++; ?>'></a>
        <div id='pricetag'>Price: <?php echo $_SESSION['cart'][$p]; $_SESSION['Tcost'] = $_SESSION['Tcost'] + (float)$_SESSION['cart'][$p] ?></div>
        <br/><?php
    }?>
    <div id='Tcost'> Total:<?php echo $_SESSION['Tcost'] ?></div><?php
}
?>
</td></tr>
<tfoot>
    <tr>
      <td>
          <form action='buy.php?clear=1'>
            <input type='hidden' name='clear' value='1'>
            <input type='submit' value='Empty Basket'>
          </form>
      </td>
    </tr>
</tfoot>
</table>
</td></tr>

</body>
</html>




