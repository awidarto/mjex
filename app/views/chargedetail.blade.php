
        Price : IDR {{Helpers::idr( (int) $order['total_price'] ) }}<br />
        Disc : IDR {{Helpers::idr( (int) $order['total_discount'] )}}<br />
        Tax : IDR {{ Helpers::idr((int) $order['total_tax'] )}}<br />
        Sub Total : IDR {{ Helpers::idr( ( (int)$order['total_price'] - (int)$order['total_discount'] ) + (int)$order['total_tax'] )}}
        <br />
        @if($order['delivery_bearer'] == 'buyer')
          Delivery charge : IDR {{ Helpers::idr( (int)$order['delivery_cost'] )}}
        @else
          Delivery charge : ditanggung toko
        @endif
        <br />
        @if($order['cod_bearer'] == 'buyer')
          COD surcharge : IDR {{ Helpers::idr( (int)$order['cod_cost'] ) }}
        @else
          COD surcharge : ditanggung toko

        @endif
        <br />
        <?php
          $total = ( (int)$order['total_price'] - (int)$order['total_discount'] ) + (int)$order['total_tax'];
          $cod = ($order['cod_bearer'] == 'buyer')?(int)$order['cod_cost']:0;
          $delivery = ($order['delivery_bearer'] == 'buyer')?(int)$order['delivery_cost']:0;
          $total = $total + $cod + $delivery;
        ?>
        <br />

