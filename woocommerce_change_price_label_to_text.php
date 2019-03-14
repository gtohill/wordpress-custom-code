/** Product displayed prices*/ 

add_filter( 'woocommerce_get_price_html', 'free_price_custom_label', 20, 2 ); 

function free_price_custom_label( $price, $product ) {
    if ( is_shop() || is_product_category() || is_product_tag() || is_product() ) {
        // HERE your custom free price label
        $free_label = '<span class="amount">' . __('Call Or Email For Quote') . '</span>';

        if( $product->is_type('variable') )
        {
            $price_min  = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_price('min') ) );
            $price_max  = wc_get_price_to_display( $product, array( 'price' => $product->get_variation_price('max') ) );

            if( $price_min != $price_max ){
                if( $price_min == 0 && $price_max > 0 )
                    $price = wc_price( $price_max );
                elseif( $price_min > 0 && $price_max == 0 )
                    $price = wc_price( $price_min );
                else
                    $price = wc_format_price_range( $price_min, $price_max );
            } else {
                if( $price_min > 0 )
                    $price = wc_price( $price_min);
                else
                    $price = $free_label;
            }
        }
        elseif( $product->is_on_sale() )
        {
            $regular_price = wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) );
            $sale_price = wc_get_price_to_display( $product, array( 'price' => $product->get_sale_price() ) );
            if( $sale_price > 0 )
                $price = wc_format_sale_price( $regular_price, $sale_price );
            else
                $price = $free_label;
        }
        else
        {
            $active_price = wc_get_price_to_display( $product, array( 'price' => $product->get_price() ) );
            if( $active_price > '' )
                $price = wc_price($active_price);
            else
                $price = $free_label;
        }
    }
    return $price;
}

// Product variation displayed prices
add_filter( 'woocommerce_variable_price_html', 'free_variation_price_custom_label', 20, 2 );

function free_variation_price_custom_label( $price, $product ) {
    // HERE your custom free price label
    $free_label = '<span class="amount">' . __('Based on contract') . '</span>';

    if( $product->is_on_sale() )
    {
        $regular_price = wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) );
        $sale_price = wc_get_price_to_display( $product, array( 'price' => $product->get_sale_price() ) );
        if( $sale_price > 0 )
            $price = wc_format_sale_price( $regular_price, $sale_price );
        else
            $price = $free_label;
    }
    else
    {
        $active_price = wc_get_price_to_display( $product, array( 'price' => $product->get_price() ) );
        if( $active_price > 0 )
            $price = wc_price($active_price);
        else
            $price = $call_label;
    }
    return $price;
}

// Cart items displayed prices and line item subtotal
add_filter( 'woocommerce_cart_item_subtotal', 'call_cart_item_price_custom_label', 20, 3 );
add_filter( 'woocommerce_cart_item_price', 'call_cart_item_price_custom_label', 20, 3 );

function call_cart_item_price_custom_label( $price, $cart_item, $cart_item_key ) {
    // HERE your custom free price label
    $call_label = '<span class="amount">' . __('Call Or Email Us A Quote') . '</span>';

    if( $cart_item['data']->get_price() > 0 )
        return $price;
    else
        return $call_label;
}

// Order items displayed prices (and also email notifications)
add_filter( 'woocommerce_order_formatted_line_subtotal', 'call_order_item_price_custom_label', 20, 3 );

function call_order_item_price_custom_label( $subtotal, $item, $order ) {
    // HERE your custom free price label
    $call_label = '<span class="amount">' . __('Call Or Email Us For A Quote') . '</span>';

    if( $order->get_line_subtotal( $item ) > 0 )
        return $subtotal;
    else
        return $call_label;
}
