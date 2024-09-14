<div class="track mt-4">
    <div class="step active">
        <span class="icon"></span>
        <span class="text"> Return Pending</span>
        <small class="text">{{$item_product['order_item_status']=='return_pending' ? $item_product['order_item_updated_at'] :''}} </small>
    </div>
    
    <div class="step">
        <span class="icon"></span>
        <span class="text">Return Processing</span>
        <small class="text">{{$item_product['order_item_status']=='return_processing' ? $item_product['order_item_updated_at'] :''}} </small>
    </div>
    
    <div class="step active">
        <span class="icon"></span>
        <span class="text">Return Complete</span>
        <small class="text">{{$item_product['order_item_status']=='return_complete' ? $item_product['order_item_updated_at'] :''}} </small>
    </div>

</div>